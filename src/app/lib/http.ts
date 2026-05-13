import axios, { isAxiosError } from "axios";

const apiBaseUrl = import.meta.env.VITE_API_BASE_URL ?? "";

export const http = axios.create({
  baseURL: apiBaseUrl,
  withCredentials: true,
  headers: {
    Accept: "application/json",
    "X-Requested-With": "XMLHttpRequest",
  },
});

type LaravelValidationError = {
  message?: string;
  errors?: Record<string, string[]>;
};

export const getRequestErrorMessage = (
  error: unknown,
  fallback = "Terjadi kesalahan. Silakan coba lagi.",
) => {
  if (isAxiosError<LaravelValidationError>(error)) {
    const data = error.response?.data;
    const firstValidationMessage = data?.errors
      ? Object.values(data.errors).flat().at(0)
      : undefined;

    return firstValidationMessage ?? data?.message ?? error.message ?? fallback;
  }

  if (error instanceof Error) {
    return error.message;
  }

  return fallback;
};
