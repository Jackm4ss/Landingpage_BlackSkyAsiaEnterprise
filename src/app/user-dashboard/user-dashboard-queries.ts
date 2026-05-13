import { useMutation, useQuery, useQueryClient } from "@tanstack/react-query";
import { authQueryKeys } from "../auth/auth-queries";
import {
  deleteMemberAccount,
  getMemberDashboard,
  removeSavedEvent,
  saveEvent,
  updateMemberAccount,
  updateMemberPassword,
  type DeleteAccountPayload,
  type UpdateAccountPayload,
  type UpdatePasswordPayload,
} from "./user-dashboard-api";

export const memberDashboardKeys = {
  root: ["member-dashboard"] as const,
};

export function useMemberDashboard(options: { enabled?: boolean } = {}) {
  return useQuery({
    queryKey: memberDashboardKeys.root,
    queryFn: getMemberDashboard,
    enabled: options.enabled ?? true,
    staleTime: 30_000,
  });
}

export function useUpdateMemberAccount() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: (payload: UpdateAccountPayload) => updateMemberAccount(payload),
    onSuccess: (response) => {
      queryClient.setQueryData(authQueryKeys.user, response.data);
      queryClient.invalidateQueries({ queryKey: memberDashboardKeys.root });
    },
  });
}

export function useUpdateMemberPassword() {
  return useMutation({
    mutationFn: (payload: UpdatePasswordPayload) => updateMemberPassword(payload),
  });
}

export function useDeleteMemberAccount() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: (payload: DeleteAccountPayload) => deleteMemberAccount(payload),
    onSuccess: () => {
      queryClient.clear();
    },
  });
}

export function useSaveEventMutation() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: saveEvent,
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: memberDashboardKeys.root });
    },
  });
}

export function useRemoveSavedEventMutation() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: removeSavedEvent,
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: memberDashboardKeys.root });
    },
  });
}
