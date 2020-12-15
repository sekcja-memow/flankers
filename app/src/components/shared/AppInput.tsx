import React, { ComponentProps } from 'react';
import { TextInput } from 'react-native-paper';

type AppInputProps = ComponentProps<typeof TextInput>;

export const AppInput: React.FC<AppInputProps> = (props) => (
  <TextInput {...props} theme={{ roundness: 8 }} />
);