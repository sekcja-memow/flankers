import React from 'react';
import { StyleSheet, View } from 'react-native';

import { UserProfilePayload } from '../../types/userProfilePayload';
import { Avatar } from '../shared/Avatar';

interface PlayerAvatarListProps {
  players: UserProfilePayload[];
}

export const PlayerAvatarList: React.FC<PlayerAvatarListProps> = ({
  players,
}) => {
  return (
    <View style={styles.container}>
      {players.map((player, i) => (
        <Avatar
          border={4}
          elevation={4}
          key={player.id}
          containerStyle={[styles.avatar, { zIndex: players.length - i }]}
          size={65}
          src={require('../../../assets/avatar.png')}
        />
      ))}
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    flexDirection: 'row',
    paddingLeft: 20,
  },
  avatar: {
    marginLeft: -20,
  },
});