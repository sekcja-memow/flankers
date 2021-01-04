import { useMutation, useQueryCache } from 'react-query';

import { QUERY_GAMES_MEMOS } from '../const/query.const';
import { GameScorePayload } from '../types/gameScorePayload';
import { useAxios } from './useAxios';

export const useRegisterGameScoreMutation = () => {
  const axios = useAxios();

  const queryCache = useQueryCache();

  return useMutation(
    (score: GameScorePayload) => axios.post('games/memos', score),
    {
      onSuccess: () => {
        queryCache.invalidateQueries(QUERY_GAMES_MEMOS);
      },
      onError(error) {
        console.log(error);
      },
    }
  );
};