"use client";

import { useEffect, useState } from "react";

const ThemeSwitch = () => {
  const [dark, setDark] = useState(false);

  useEffect(() => {
    const stored = localStorage.getItem("theme");
    if (stored === "dark") {
      setDark(true);
      document.documentElement.classList.add("dark");
    }
  }, []);

  useEffect(() => {
    if (dark) {
      document.documentElement.classList.add("dark");
      localStorage.setItem("theme", "dark");
    } else {
      document.documentElement.classList.remove("dark");
      localStorage.setItem("theme", "light");
    }
  }, [dark]);

  return (
    <button
      onClick={() => setDark(!dark)}
      className={`
        relative h-7 w-14 rounded-full p-1 transition-colors duration-200 cursor-pointer
        ${dark ? "bg-[var(--secondary)]" : "bg-gray-300"}
      `}
    >
      <div
        className={`
          flex h-5 w-5 items-center justify-center rounded-full
          transition-transform duration-200
          ${
            dark
              ? "translate-x-7 bg-[var(--primary)]"
              : "translate-x-0 bg-white"
          }
        `}
      >
        {dark ? (
          // 🌙 Ícone Lua
          <svg
            xmlns="http://www.w3.org/2000/svg"
            width="16"
            height="16"
            fill="currentColor"
            viewBox="0 0 24 24"
            className="text-[var(--primary-foreground)]"
          >
            <path d="M21 12.79A9 9 0 1 1 11.21 3a7 7 0 1 0 9.79 9.79z" />
          </svg>
        ) : (
          // ☀️ Ícone Sol
          <svg
            xmlns="http://www.w3.org/2000/svg"
            width="16"
            height="16"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            strokeWidth="2"
            strokeLinecap="round"
            strokeLinejoin="round"
            className="lucide lucide-sun text-[var(--primary)]"
          >
            <circle cx="12" cy="12" r="4"></circle>
            <path d="M12 2v2"></path>
            <path d="M12 20v2"></path>
            <path d="m4.93 4.93 1.41 1.41"></path>
            <path d="m17.66 17.66 1.41 1.41"></path>
            <path d="M2 12h2"></path>
            <path d="M20 12h2"></path>
            <path d="m6.34 17.66-1.41 1.41"></path>
            <path d="m19.07 4.93-1.41 1.41"></path>
          </svg>
        )}
      </div>
      <span className="sr-only">Alternar tema</span>
    </button>
  );
};

export default ThemeSwitch;