import { type CSSProperties } from "react";

type AuthStudioVisualPanelProps = {
  ariaLabel: string;
  image: string;
  title: string;
  description: string;
  cardTitle: string;
  cardDescription: string;
};

type AuthInfoCardProps = {
  title: string;
  description: string;
};

function InfoShape() {
  return (
    <svg
      className="login-page__info-shape"
      width="1094"
      height="249"
      viewBox="0 0 1094 249"
      preserveAspectRatio="none"
      fill="none"
      xmlns="http://www.w3.org/2000/svg"
      aria-hidden="true"
    >
      <path
        d="M0.263672 16.8809C0.263672 8.0443 7.42712 0.880859 16.2637 0.880859H786.394H999.115C1012.37 0.880859 1023.12 11.626 1023.12 24.8808L1023.12 47.3809C1023.12 60.6357 1033.86 71.3809 1047.12 71.3809H1069.6C1082.85 71.3809 1093.6 82.126 1093.6 95.3809L1093.6 232.881C1093.6 241.717 1086.43 248.881 1077.6 248.881H16.2637C7.42716 248.881 0.263672 241.717 0.263672 232.881V16.8809Z"
        fill="white"
      />
    </svg>
  );
}

export function AuthInfoCard({ title, description }: AuthInfoCardProps) {
  return (
    <div className="login-page__info-panel login-page__info-panel--studio">
      <InfoShape />
      <div className="login-page__info-content">
        <p className="login-page__info-title">{title}</p>
        <p className="login-page__info-copy">{description}</p>
      </div>
    </div>
  );
}

export function AuthStudioVisualPanel({
  ariaLabel,
  image,
  title,
  description,
  cardTitle,
  cardDescription,
}: AuthStudioVisualPanelProps) {
  return (
    <section
      className="login-page__visual-side login-page__visual-side--studio"
      aria-label={ariaLabel}
      style={{ "--login-visual-image": `url(${image})` } as CSSProperties}
    >
      <div
        className="login-page__visual-panel login-page__visual-panel--studio"
      >
        <div className="login-page__visual-copy login-page__visual-copy--studio">
          <h2>{title}</h2>
          <p>{description}</p>
        </div>

        <AuthInfoCard title={cardTitle} description={cardDescription} />
      </div>
    </section>
  );
}
