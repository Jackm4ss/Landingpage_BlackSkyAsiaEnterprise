import { type FormEvent, type MouseEvent, useState } from "react";
import { Input } from "../components/ui/input";
import { Label } from "../components/ui/label";
import { requestPasswordReset } from "../auth/session";
import logo from "../../assets/LOGO.png";
import heroImage from "../../assets/hero-concert-bg.png";
import { AuthStudioVisualPanel } from "./AuthStudioVisualPanel";
import "./AuthPages.css";

type ForgotPasswordPageProps = {
  onNavigate: (path: string) => void;
};

export function ForgotPasswordPage({ onNavigate }: ForgotPasswordPageProps) {
  const [email, setEmail] = useState("");
  const [error, setError] = useState("");
  const [message, setMessage] = useState("");
  const [isSubmitting, setIsSubmitting] = useState(false);

  const handleSubmit = async (event: FormEvent<HTMLFormElement>) => {
    event.preventDefault();
    setError("");
    setMessage("");
    setIsSubmitting(true);

    try {
      const normalizedEmail = await requestPasswordReset({ email });
      setMessage(`Reset instructions sent to ${normalizedEmail}.`);
    } catch (caughtError) {
      setError(
        caughtError instanceof Error
          ? caughtError.message
          : "Gagal mengirim reset link.",
      );
    } finally {
      setIsSubmitting(false);
    }
  };

  const handleLoginLink = (event: MouseEvent<HTMLAnchorElement>) => {
    event.preventDefault();
    onNavigate("/login");
  };

  const handleResetLink = (event: MouseEvent<HTMLAnchorElement>) => {
    event.preventDefault();
    onNavigate("/reset-password");
  };

  return (
    <main className="login-page">
      <section className="login-page__form-side" aria-label="Forgot password form">
        <div className="login-page__form-card login-page__form-card--compact">
          <a className="login-page__brand" href="/" aria-label="Black Sky Enterprise">
            <img src={logo} alt="" aria-hidden="true" />
          </a>

          <div className="login-page__intro">
            <h1>Forgot Password?</h1>
            <p>No worries, we'll send you reset instructions.</p>
          </div>

          <form className="auth-form login-page__form" onSubmit={handleSubmit} noValidate>
            <div className="auth-form__field">
              <Label className="auth-form__label" htmlFor="forgot-email">
                Email address*
              </Label>
              <Input
                className="auth-form__input"
                id="forgot-email"
                type="email"
                value={email}
                autoComplete="email"
                placeholder="Enter your email address"
                onChange={(event) => setEmail(event.target.value)}
              />
            </div>

            <p
              className={error ? "auth-form__alert" : "auth-form__success"}
              role={error ? "alert" : "status"}
            >
              {error || message}
              {message ? (
                <>
                  {" "}
                  <a
                    className="auth-form__link"
                    href="/reset-password"
                    onClick={handleResetLink}
                  >
                    Open reset form
                  </a>
                </>
              ) : null}
            </p>

            <button className="auth-form__button" type="submit" disabled={isSubmitting}>
              {isSubmitting ? "Sending" : "Send Reset Link"}
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
        ariaLabel="Black Sky password recovery"
        image={heroImage}
        title="Don't worry it happens! Resetting your password is quick and easy."
        description="Just enter your registered email address below, and we'll send you a secure link to reset your password. Follow the instructions in the email, and you'll be back in your account in no time!"
        cardTitle="Follow the instructions"
        cardDescription="If you don't see the email in your inbox, be sure to check your spam or junk folder."
      />
    </main>
  );
}
