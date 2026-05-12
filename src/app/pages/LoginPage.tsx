import { type CSSProperties, type FormEvent, type MouseEvent, useState } from "react";
import { Eye, EyeOff } from "lucide-react";
import { Checkbox } from "../components/ui/checkbox";
import { Input } from "../components/ui/input";
import { Label } from "../components/ui/label";
import { createLoginSession } from "../auth/session";
import logo from "../../assets/LOGO.png";
import heroImage from "../../assets/hero-concert-bg.png";
import { AuthInfoCard } from "./AuthStudioVisualPanel";
import "./AuthPages.css";

type LoginPageProps = {
  onNavigate: (path: string) => void;
};

export function LoginPage({ onNavigate }: LoginPageProps) {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [remember, setRemember] = useState(false);
  const [showPassword, setShowPassword] = useState(false);
  const [error, setError] = useState("");
  const [isSubmitting, setIsSubmitting] = useState(false);

  const handleSubmit = async (event: FormEvent<HTMLFormElement>) => {
    event.preventDefault();
    setError("");
    setIsSubmitting(true);

    try {
      await createLoginSession({ email, password, remember });
      onNavigate("/login/success");
    } catch (caughtError) {
      setError(caughtError instanceof Error ? caughtError.message : "Login gagal.");
    } finally {
      setIsSubmitting(false);
    }
  };

  const handleRegisterLink = (event: MouseEvent<HTMLAnchorElement>) => {
    event.preventDefault();
    onNavigate("/register");
  };

  const handleForgotPasswordLink = (event: MouseEvent<HTMLAnchorElement>) => {
    event.preventDefault();
    onNavigate("/forgot-password");
  };

  const PasswordIcon = showPassword ? EyeOff : Eye;

  return (
    <main className="login-page">
      <section className="login-page__form-side" aria-label="Login form">
        <div className="login-page__form-card">
          <a className="login-page__brand" href="/" aria-label="Black Sky Enterprise">
            <img src={logo} alt="" aria-hidden="true" />
          </a>

          <div className="login-page__intro">
            <h1>Welcome Back</h1>
            <p>Welcome back! Enter your email and password to continue.</p>
          </div>

          <form className="auth-form login-page__form" onSubmit={handleSubmit} noValidate>
            <div className="auth-form__field">
              <Label className="auth-form__label" htmlFor="login-email">
                Email address*
              </Label>
              <Input
                className="auth-form__input"
                id="login-email"
                type="email"
                value={email}
                autoComplete="email"
                placeholder="Enter your email address"
                onChange={(event) => setEmail(event.target.value)}
              />
            </div>

            <div className="auth-form__field">
              <Label className="auth-form__label" htmlFor="login-password">
                Password*
              </Label>
              <div className="auth-form__password-wrap">
                <Input
                  className="auth-form__input"
                  id="login-password"
                  type={showPassword ? "text" : "password"}
                  value={password}
                  autoComplete="current-password"
                  placeholder="Password"
                  onChange={(event) => setPassword(event.target.value)}
                />
                <button
                  className="auth-form__icon-button"
                  type="button"
                  aria-label={showPassword ? "Hide password" : "Show password"}
                  onClick={() => setShowPassword((current) => !current)}
                >
                  <PasswordIcon size={17} aria-hidden="true" />
                </button>
              </div>
            </div>

            <div className="auth-form__row">
              <Label className="auth-form__checkline" htmlFor="login-remember">
                <Checkbox
                  className="auth-form__checkbox"
                  id="login-remember"
                  checked={remember}
                  onCheckedChange={(checked) => setRemember(checked === true)}
                />
                Remember Me
              </Label>
              <a
                className="auth-form__link"
                href="/forgot-password"
                onClick={handleForgotPasswordLink}
              >
                Forgot Password?
              </a>
            </div>

            <p className="auth-form__alert" role="alert">
              {error}
            </p>

            <button className="auth-form__button" type="submit" disabled={isSubmitting}>
              {isSubmitting ? "Signing in" : "Sign in to Black Sky"}
            </button>
          </form>

          <p className="auth-page__switch login-page__switch">
            New on our platform?{" "}
            <a href="/register" onClick={handleRegisterLink}>
              Create an account
            </a>
          </p>
        </div>
      </section>

      <section
        className="login-page__visual-side"
        aria-label="Black Sky membership"
        style={{ "--login-visual-image": `url(${heroImage})` } as CSSProperties}
      >
        <div className="login-page__visual-panel">
          <div className="login-page__visual-copy">
            <h2>Welcome back! Please sign in to your Black Sky account</h2>
            <p>
              Thank you for joining our live entertainment network. Activate your access,
              track upcoming shows, and keep your ticket journey close.
            </p>
          </div>

          <AuthInfoCard
            title="Please enter your login details"
            description="Stay connected with Black Sky Enterprise. Subscribe now for the latest concert updates and news."
          />
        </div>
      </section>
    </main>
  );
}
