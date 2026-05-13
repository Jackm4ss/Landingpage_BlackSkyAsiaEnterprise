import { useMutation, useQuery, useQueryClient } from "@tanstack/react-query";
import { authApi } from "./auth-api";

export const authQueryKeys = {
  user: ["auth", "user"] as const,
};

export const useCurrentUser = () => {
  return useQuery({
    queryKey: authQueryKeys.user,
    queryFn: authApi.currentUser,
    retry: false,
  });
};

export const useLoginMutation = () => {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: authApi.login,
    onSuccess: (user) => {
      queryClient.setQueryData(authQueryKeys.user, user);
    },
  });
};

export const useRegisterMutation = () => {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: authApi.register,
    onSuccess: (user) => {
      queryClient.setQueryData(authQueryKeys.user, user);
    },
  });
};

export const useForgotPasswordMutation = () => {
  return useMutation({
    mutationFn: authApi.forgotPassword,
  });
};

export const useResetPasswordMutation = () => {
  return useMutation({
    mutationFn: authApi.resetPassword,
  });
};

export const useResendEmailVerificationMutation = () => {
  return useMutation({
    mutationFn: authApi.resendEmailVerification,
  });
};

export const useLogoutMutation = () => {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: authApi.logout,
    onSuccess: () => {
      queryClient.setQueryData(authQueryKeys.user, null);
    },
  });
};
