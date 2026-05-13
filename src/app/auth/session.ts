const AUTH_STORAGE_KEY = "black-sky.auth.session";
const EMAIL_PATTERN = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

export type AuthSession = {
  name?: string;
  email: string;
  phone?: string;
  avatar?: string;
  signedInAt: string;
};

export type LoginCredentials = {
  email: string;
  password: string;
  remember: boolean;
};

export type RegisterCredentials = {
  name: string;
  email: string;
  password: string;
  acceptedTerms: boolean;
};

export type PasswordResetRequest = {
  email: string;
};

export type PasswordResetCredentials = {
  password: string;
  passwordConfirmation: string;
};

const isBrowser = () => typeof window !== "undefined";

const safeRead = (storage: Storage) => {
  try {
    const storedValue = storage.getItem(AUTH_STORAGE_KEY);
    if (!storedValue) return null;

    return JSON.parse(storedValue) as AuthSession;
  } catch {
    return null;
  }
};

const safeWrite = (storage: Storage, session: AuthSession) => {
  try {
    storage.setItem(AUTH_STORAGE_KEY, JSON.stringify(session));
  } catch {
    storage.removeItem(AUTH_STORAGE_KEY);
  }
};

export const getCurrentSession = () => {
  if (!isBrowser()) return null;

  return safeRead(window.localStorage) ?? safeRead(window.sessionStorage);
};

export const clearCurrentSession = () => {
  if (!isBrowser()) return;

  window.localStorage.removeItem(AUTH_STORAGE_KEY);
  window.sessionStorage.removeItem(AUTH_STORAGE_KEY);
};

export const createLoginSession = async ({
  email,
  password,
  remember,
}: LoginCredentials) => {
  const normalizedEmail = email.trim().toLowerCase();

  if (!EMAIL_PATTERN.test(normalizedEmail)) {
    throw new Error("Masukkan email yang valid.");
  }

  if (!password.trim()) {
    throw new Error("Masukkan password.");
  }

  await new Promise((resolve) => setTimeout(resolve, 220));

  const session: AuthSession = {
    email: normalizedEmail,
    signedInAt: new Date().toISOString(),
  };

  if (!isBrowser()) return session;

  window.localStorage.removeItem(AUTH_STORAGE_KEY);
  window.sessionStorage.removeItem(AUTH_STORAGE_KEY);

  safeWrite(remember ? window.localStorage : window.sessionStorage, session);

  return session;
};

export const createRegisterSession = async ({
  name,
  email,
  password,
  acceptedTerms,
}: RegisterCredentials) => {
  const normalizedName = name.trim();
  const normalizedEmail = email.trim().toLowerCase();

  if (normalizedName.length < 2) {
    throw new Error("Masukkan nama minimal 2 karakter.");
  }

  if (!EMAIL_PATTERN.test(normalizedEmail)) {
    throw new Error("Masukkan email yang valid.");
  }

  if (password.length < 8) {
    throw new Error("Password minimal 8 karakter.");
  }

  if (!acceptedTerms) {
    throw new Error("Setujui Terms & Conditions terlebih dahulu.");
  }

  await new Promise((resolve) => setTimeout(resolve, 260));

  const session: AuthSession = {
    name: normalizedName,
    email: normalizedEmail,
    signedInAt: new Date().toISOString(),
  };

  if (!isBrowser()) return session;

  window.localStorage.removeItem(AUTH_STORAGE_KEY);
  window.sessionStorage.removeItem(AUTH_STORAGE_KEY);
  safeWrite(window.localStorage, session);

  return session;
};

export const requestPasswordReset = async ({ email }: PasswordResetRequest) => {
  const normalizedEmail = email.trim().toLowerCase();

  if (!EMAIL_PATTERN.test(normalizedEmail)) {
    throw new Error("Masukkan email yang valid.");
  }

  await new Promise((resolve) => setTimeout(resolve, 240));

  return normalizedEmail;
};

export const resetPassword = async ({
  password,
  passwordConfirmation,
}: PasswordResetCredentials) => {
  if (password.length < 8) {
    throw new Error("Password minimal 8 karakter.");
  }

  if (password !== passwordConfirmation) {
    throw new Error("Konfirmasi password belum sama.");
  }

  await new Promise((resolve) => setTimeout(resolve, 260));
};
