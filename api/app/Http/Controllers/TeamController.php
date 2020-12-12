<?php

namespace App\Http\Controllers\Teamwork;

use App\Http\Message;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateTeamRequest;

class TeamController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display list o fuser teams
     *
     * @group Team management
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Auth::user()->teams;
    }

    /**
     * Create new team
     *
     * User which is creating the team will be set as its owner
     *
     * @group Team management
     * @bodyParam name string required Team name Example: Flankersi
     * @bodyParam description string Team description Example: Best team ever
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateTeamRequest $request)
    {
        $teamModel = config('teamwork.team_model');
        $payload = $request->only(['name', 'description']);
        $data = array_merge($payload, [
            'owner_id' => $request->user()->getKey(),
        ]);

        $team = $teamModel::create($data);
        $request->user()->attachTeam($team);

        return Message::ok('Team created successfully', $team);
    }

    /**
     * Update specific team
     *
     * @group Team management
     * @urlParam teamId int required
     * @bodyParam name string Team name Example: Flankersi
     * @bodyParam description string Team description Example: Best team ever
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateTeamRequest $request, int $id)
    {
        $teamModel = config('teamwork.team_model');
        $team = $teamModel::findOrFail($id);

        if (!Auth::user()->isOwnerOfTeam($team->id)) {
            return Message::error(403, 'Only owner can update this team');
        }

        $team->fill($request->all());
        $team->save();

        return Message::ok('Team updated successfully');
    }

    /**
     * Delete a team
     *
     * @group Team management
     * @urlParam teamId int required
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $teamModel = config('teamwork.team_model');
        $team = $teamModel::findOrFail($id);

        if (!Auth::user()->isOwnerOfTeam($team)) {
            return Message::error(403, 'Only owner can update this team.');
        }

        $team->delete();

        $userModel = config('teamwork.user_model');
        $userModel::where('current_team_id', $id)
            ->update(['current_team_id' => null]);

        return Message::ok('Team deleted successfully');
    }
}
