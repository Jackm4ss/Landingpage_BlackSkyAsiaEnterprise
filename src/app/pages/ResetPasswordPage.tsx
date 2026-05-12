import { type FormEvent, type MouseEvent, useState } from "react";
import { Eye, EyeOff } from "lucide-react";
import { Input } from "../components/ui/input";
import { Label } from "../components/ui/label";
import { resetPassword } from "../auth/session";
import logo from "../../assets/LOGO.png";
import heroImage from "../../assets/hero-concert-bg.png";
import { AuthStudioVisualPanel } from "./AuthStudioVisualPanel";
import "./AuthPages.css";

type ResetPasswordPageProps = {
  onNavigate: (path: string) => void;
};

type PasswordFieldProps = {
  id: string;
  label: string;
  value: string;
  isVisible: boolean;
  autoComplete: string;
  onChange: (value: string) => void;
  onToggle: () => void;
};

const PasswordField = ({
  id,
  label,
  value,
  isVisible,
  autoComplete,
  onChange,
  onToggle,
}: PasswordFieldProps) => {
  const Icon = isVisible ? EyeOff : Eye;

  return (
    <div className="auth-form__field">
      <Label className="auth-form__label" htmlFor={id}>
        {label}
      </Label>
      <div className="auth-form__password-wrap">
        <Input
          className="auth-form__input"
          id={id}
          type={isVisible ? "text" : "password"}
          value={value}
          autoComplete={autoComplete}
          placeholder="Password"
          onChange={(event) => onChange(event.target.value)}
        />
        <button
          className="auth-form__icon-button"
          type="button"
          aria-label={isVisible ? "Hide password" : "Show password"}
          onClick={onToggle}
        >
          <Icon size={17} aria-hidden="true" />
        </button>
      </div>
    </div>
  );
};

export function ResetPasswordPage({ onNavigate }: ResetPasswordPageProps) {
  const [password, setPassword] = useState("");
  const [passwordConfirmation, setPasswordConfirmation] = useState("");
  const [showPassword, setShowPassword] = useState(false);
  const [showPasswordConfirmation, setShowPasswordConfirmation] = useState(false);
  const [error, setError] = useState("");
  const [message, setMessage] = useState("");
  const [isSubmitting, setIsSubmitting] = useState(false);

  const handleSubmit = async (event: FormEvent<HTMLFormElement>) => {
    event.preventDefault();
    setError("");
    setMessage("");
    setIsSubmitting(true);

    try {
      await resetPassword({ password, passwordConfirmation });
      setMessage("Password updated. You can sign in now.");
      setPassword("");
      setPasswordConfirmation("");
    } catch (caughtError) {
      setError(
        caughtError instanceof Error ? caughtError.message : "Reset password gagal.",
      );
    } finally {
      setIsSubmitting(false);
    }
  };

  const handleLoginLink = (event: MouseEvent<HTMLAnchorElement>) => {
    event.preventDefault();
    onNavigate("/login");
  };

  return (
    <main className="login-page">
      <section className="login-page__form-side" aria-label="Reset password form">
        <div className="login-page__form-card login-page__form-card--reset">
          <a className="login-page__brand" href="/" aria-label="Black Sky Enterprise">
            <img src={logo} alt="" aria-hidden="true" />
          </a>

          <div className="login-page__intro">
            <h1>Set new password</h1>
            <p>Must be at least 8 characters.</p>
          </div>

          <form className="auth-form login-page__form" onSubmit={handleSubmit} noValidate>
            <PasswordField
              id="reset-password"
              label="New Password*"
              value={password}
              isVisible={showPassword}
              autoComplete="new-password"
              onChange={setPassword}
              onToggle={() => setShowPassword((current) => !current)}
            />

            <PasswordField
              id="reset-password-confirmation"
              label="Confirm Password*"
              value={passwordConfirmation}
              isVisible={showPasswordConfirmation}
              autoComplete="new-password"
              onChange={setPasswordConfirmation}
              onToggle={() => setShowPasswordConfirmation((current) => !current)}
            />

            <p
              className={error ? "auth-form__alert" : "auth-form__success"}
              role={error ? "alert" : "status"}
            >
              {error || message}
            </p>

            <button className="auth-form__button" type="submit" disabled={isSubmitting}>
              {isSubmitting ? "Resetting" : "Reset Password"}
            </button>

            <a
              className="auth-form__button auth-form__button--ghost"
              href="/login"
              onClick={handleLoginLink}
            >
              Back to login
            </a>
          </form>
        </div>
      </section>

      <AuthStudioVisualPanel
        ariaLabel="Black Sky password reset"
        image={heroImage}
        title="You're just one step away from regaining access to your account."
        description="Please create a new password below. Make sure it's strong and secure, combining letters, numbers, and special characters."
        cardTitle="Reset Your Password"
        cardDescription="You're just one step away from regaining access to your account."
      />
    </main>
  );
}
