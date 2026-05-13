import { Link } from "react-router";
import logo from "../../assets/LOGO.png";
import "./AuthPages.css";

export function LoginSuccessPage() {
  return (
    <main className="auth-success-page" aria-label="Login success">
      <section className="auth-success-page__card">
        <img src={logo} alt="" aria-hidden="true" />
        <p>OK login user berhasil</p>
        <Link to="/">Kembali ke halaman utama</Link>
      </section>
    </main>
  );
}
