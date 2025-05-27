"use client";

import { Outlet } from "react-router-dom";
import { Sidebar } from "@/components/Sidebar";

export default function DashboardLayout() {
  return (
    <div className="flex-col sm:flex">
      <Sidebar />
      <main className="ml-1 sm:ml-14 p-4 w-[cal(100% - 56px)] flex flex-col transition-all">
        <Outlet />
      </main>
    </div>
  );
}
