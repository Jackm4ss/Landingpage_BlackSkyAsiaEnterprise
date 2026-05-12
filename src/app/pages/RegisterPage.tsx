import { type CSSProperties, type FormEvent, type MouseEvent, useState } from "react";
import { Eye, EyeOff } from "lucide-react";
import { Checkbox } from "../components/ui/checkbox";
import { Input } from "../components/ui/input";
import { Label } from "../components/ui/label";
import { createRegisterSession } from "../auth/session";
import logo from "../../assets/LOGO.png";
import heroImage from "../../assets/hero-concert-bg.png";
import { AuthInfoCard } from "./AuthStudioVisualPanel";
import "./AuthPages.css";

type RegisterPageProps = {
  onNavigate: (path: string) => void;
};

export function RegisterPage({ onNavigate }: RegisterPageProps) {
  const [name, setName] = useState("");
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [acceptedTerms, setAcceptedTerms] = useState(false);
  const [showPassword, setShowPassword] = useState(false);
  const [error, setError] = useState("");
  const [isSubmitting, setIsSubmitting] = useState(false);

  const handleSubmit = async (event: FormEvent<HTMLFormElement>) => {
    event.preventDefault();
    setError("");
    setIsSubmitting(true);

    try {
      await createRegisterSession({ name, email, password, acceptedTerms });
      onNavigate("/login/success");
    } catch (caughtError) {
      setError(caughtError instanceof Error ? caughtError.message : "Registrasi gagal.");
    } finally {
      setIsSubmitting(false);
    }
  };

  const handleLoginLink = (event: MouseEvent<HTMLAnchorElement>) => {
    event.preventDefault();
    onNavigate("/login");
  };

  const handleStaticLink = (event: MouseEvent<HTMLAnchorElement>) => {
    event.preventDefault();
  };

  const PasswordIcon = showPassword ? EyeOff : Eye;

  return (
    <main className="login-page">
      <section className="login-page__form-side" aria-label="Register form">
        <div className="login-page__form-card login-page__form-card--register">
          <a className="login-page__brand" href="/" aria-label="Black Sky Enterprise">
            <img src={logo} alt="" aria-hidden="true" />
          </a>

          <div className="login-page__intro">
            <h1>Create an account</h1>
            <p>Let's get started. Fill in the details below to create your account.</p>
          </div>

          <form className="auth-form login-page__form" onSubmit={handleSubmit} noValidate>
            <div className="auth-form__field">
              <Label className="auth-form__label" htmlFor="register-name">
                Name*
              </Label>
              <Input
                className="auth-form__input"
                id="register-name"
                type="text"
                value={name}
                autoComplete="name"
                placeholder="Enter your full name"
                onChange={(event) => setName(event.target.value)}
              />
            </div>

            <div className="auth-form__field">
              <Label className="auth-form__label" htmlFor="register-email">
                Email address*
              </Label>
              <Input
                className="auth-form__input"
                id="register-email"
                type="email"
                value={email}
                autoComplete="email"
                placeholder="Enter your email address"
                onChange={(event) => setEmail(event.target.value)}
              />
            </div>

            <div className="auth-form__field">
              <Label className="auth-form__label" htmlFor="register-password">
                Password*
              </Label>
              <div className="auth-form__password-wrap">
                <Input
                  className="auth-form__input"
                  id="register-password"
                  type={showPassword ? "text" : "password"}
                  value={password}
                  autoComplete="new-password"
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
              <p className="auth-form__hint">Minimum 8 characters.</p>
            </div>

            <div className="auth-form__row login-page__terms-row">
              <Label className="auth-form__checkline" htmlFor="register-terms">
                <Checkbox
                  className="auth-form__checkbox"
                  id="register-terms"
                  checked={acceptedTerms}
                  onCheckedChange={(checked) => setAcceptedTerms(checked === true)}
                />
                <span>
                  I agree to the{" "}
                  <a className="auth-form__link" href="#terms" onClick={handleStaticLink}>
                    Terms & Conditions
                  </a>
                </span>
              </Label>
            </div>

            <p className="auth-form__alert" role="alert">
              {error}
            </p>

            <button className="auth-form__button" type="submit" disabled={isSubmitting}>
              {isSubmitting ? "Signing up" : "Create Black Sky account"}
            </button>
          </form>

          <p className="auth-page__switch login-page__switch">
            Already have account?{" "}
            <a href="/login" onClick={handleLoginLink}>
              Sign in
            </a>
          </p>
        </div>
      </section>

      <section
        className="login-page__visual-side"
        aria-label="Black Sky registration"
        style={{ "--login-visual-image": `url(${heroImage})` } as CSSProperties}
      >
        <div className="login-page__visual-panel">
          <div className="login-page__visual-copy">
            <h2>Create your Black Sky account and join the show</h2>
            <p>
              Register once to unlock ticket history, event alerts, and curated access
              for upcoming live experiences across Southeast Asia.
            </p>
          </div>

          <AuthInfoCard
            title="Start your member access"
            description="Stay connected with Black Sky Enterprise. Get first looks at new concerts, venue updates, and ticket releases."
          />
        </div>
      </section>
    </main>
  );
}
