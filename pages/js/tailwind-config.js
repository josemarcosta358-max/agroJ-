tailwind.config = {
  darkMode: "class",
  theme: {
    extend: {
      colors: {
        "on-surface-variant": "#404940","on-tertiary-fixed": "#00210e","inverse-surface": "#2e3132",
        "tertiary-fixed-dim": "#9cd3a9","surface-container-lowest": "#ffffff","surface-container-highest": "#e1e3e4",
        "tertiary-fixed": "#b7f0c4","surface-container-low": "#f3f4f5","inverse-primary": "#8cd79b",
        "on-background": "#191c1d","secondary-fixed": "#ffdfa0","surface-bright": "#f8f9fa",
        "surface-container-high": "#e7e8e9","on-primary": "#ffffff","inverse-on-surface": "#f0f1f2",
        "secondary": "#795900","outline-variant": "#bfc9bd","on-tertiary-fixed-variant": "#1c5030",
        "on-error": "#ffffff","secondary-fixed-dim": "#f6be39","on-secondary-fixed": "#261a00",
        "tertiary": "#1b5030","surface": "#f8f9fa","on-secondary": "#ffffff","surface-tint": "#206c3b",
        "on-secondary-container": "#715300","primary-fixed-dim": "#8cd79b","on-primary-fixed-variant": "#005226",
        "on-tertiary-container": "#ade5b9","primary-container": "#1f6b3a","error": "#ba1a1a",
        "outline": "#707a6f","surface-dim": "#d9dadb","on-primary-fixed": "#00210c","background": "#f8f9fa",
        "on-surface": "#191c1d","tertiary-container": "#356846","primary": "#005226","error-container": "#ffdad6",
        "on-primary-container": "#9ce9ab","surface-container": "#edeeef","on-error-container": "#93000a",
        "secondary-container": "#ffc641","primary-fixed": "#a7f4b6","on-secondary-fixed-variant": "#5c4300",
        "surface-variant": "#e1e3e4","on-tertiary": "#ffffff"
      },
      borderRadius: { DEFAULT: "0.25rem", lg: "0.5rem", xl: "0.75rem", full: "9999px" },
      spacing: { "2xl":"48px","xs":"4px","lg":"24px","margin-mobile":"16px","md":"16px","xl":"32px","sm":"8px","margin-desktop":"32px","base":"8px","gutter":"16px" },
      fontFamily: { "headline-md":["Inter"],"label-md":["Inter"],"headline-sm":["Inter"],"headline-lg-mobile":["Inter"],"headline-lg":["Inter"],"body-lg":["Inter"],"label-lg":["Inter"],"body-md":["Inter"],"body-sm":["Inter"] },
      fontSize: {
        "headline-md": ["24px", {"lineHeight":"32px","fontWeight":"600"}],
        "label-md": ["14px", {"lineHeight":"18px","fontWeight":"500"}],
        "headline-sm": ["20px", {"lineHeight":"28px","fontWeight":"600"}],
        "headline-lg-mobile": ["24px", {"lineHeight":"32px","fontWeight":"700"}],
        "headline-lg": ["32px", {"lineHeight":"40px","fontWeight":"700"}],
        "body-lg": ["18px", {"lineHeight":"28px","fontWeight":"400"}],
        "label-lg": ["16px", {"lineHeight":"20px","fontWeight":"600"}],
        "body-md": ["16px", {"lineHeight":"24px","fontWeight":"400"}],
        "body-sm": ["13px", {"lineHeight":"18px","fontWeight":"400"}]
      }
    }
  }
}