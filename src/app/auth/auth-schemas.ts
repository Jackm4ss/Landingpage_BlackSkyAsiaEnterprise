import { z } from "zod";

const emptyStringWhenMissing = (value: unknown) =>
  typeof value === "string" ? value : "";

const falseWhenMissing = (value: unknown) => value === true;

const emailSchema = z
  .preprocess(
    emptyStringWhenMissing,
    z
      .string()
      .trim()
      .min(1, "Email wajib diisi.")
      .email("Masukkan email yang valid.")
      .toLowerCase(),
  );

const passwordSchema = z
  .preprocess(
    emptyStringWhenMissing,
    z
      .string()
      .min(1, "Password wajib diisi.")
      .min(8, "Password minimal 8 karakter."),
  );

export const loginSchema = z.object({
  email: emailSchema,
  password: z.preprocess(
    emptyStringWhenMissing,
    z.string().min(1, "Password wajib diisi."),
  ),
  remember: z.preprocess(falseWhenMissing, z.boolean()),
});

export const registerSchema = z.object({
  name: z.preprocess(
    emptyStringWhenMissing,
    z
      .string()
      .trim()
      .min(1, "Nama wajib diisi.")
      .min(2, "Nama minimal 2 karakter.")
      .max(120, "Nama terlalu panjang."),
  ),
  email: emailSchema,
  password: passwordSchema,
  acceptedTerms: z.preprocess(falseWhenMissing, z.boolean()).refine((value) => value, {
    message: "Setujui Terms & Conditions terlebih dahulu.",
  }),
});

export const forgotPasswordSchema = z.object({
  email: emailSchema,
});

export const resetPasswordSchema = z
  .object({
    password: passwordSchema,
    passwordConfirmation: z.preprocess(
      emptyStringWhenMissing,
      z.string().min(1, "Konfirmasi password wajib diisi."),
    ),
  })
  .refine((value) => value.password === value.passwordConfirmation, {
    message: "Konfirmasi password belum sama.",
    path: ["passwordConfirmation"],
  });

export type LoginFormValues = z.infer<typeof loginSchema>;
export type RegisterFormValues = z.infer<typeof registerSchema>;
export type ForgotPasswordFormValues = z.infer<typeof forgotPasswordSchema>;
export type ResetPasswordFormValues = z.infer<typeof resetPasswordSchema>;
