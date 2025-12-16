# 🚀 AccoSpark  
**An AI Accountability System That Adapts to Real Life**

AccoSpark is a goal-oriented accountability platform designed to help users actually follow through on long-term goals — without relying on streaks, session timers, or constant app usage.

Instead of pushing notifications inside an app, AccoSpark integrates directly with **Google Calendar** and adapts task workload based on **real user behavior**, not motivation hacks.

---

## ✨ Core Philosophy

> **Accountability should adapt, not punish.**

People miss days. Life happens.  
AccoSpark treats missed tasks as **data**, not failure.

---

## 🧠 How AccoSpark Works

### 1️⃣ Goal → Roadmap
- User defines a goal and target timeline
- AI breaks it into **realistic, time-aware tasks**
- Each task has an estimated duration (`estimated_minutes`)

### 2️⃣ Calendar-First Execution
- Tasks are synced to **Google Calendar**
- Users get native reminders (no mobile app required)
- Calendar events feel intentional, not spammy

### 3️⃣ Daily Check-In (No Sessions)
- Short end-of-day reflection
- User reports:
  - What went well
  - Energy & mood
  - Whether tasks were completed
- No timers. No forced “start session”.

### 4️⃣ Adaptive Recovery Logic
- Background system evaluates:
  - Planned vs completed tasks
  - Missed days
  - Consecutive failures
  - Time overload signals
- Workload is **automatically adjusted**:
  - Reschedule tasks
  - Reduce future workload
  - Freeze system-generated pressure if needed

### 5️⃣ Persona-Driven Feedback
- User chooses **one AI persona**
- Persona tone never changes (trust is preserved)
- Only **intensity and strictness adapt** over time

---

## 🏗️ Architecture Overview

### 🖥️ Application Layer
- **Laravel 12** – Backend, auth, APIs
- **Blade + Tailwind CSS** – UI & UX
- **MySQL** – Persistent data storage

### 🔌 Integrations
- **Google OAuth** – User authentication
- **Google Calendar API**
  - Task scheduling
  - Native notifications
- **AI Models**
  - Task breakdown
  - Persona-aware feedback
  - Daily review summaries

---

## 🔐 Admin System (Overwatch)

- Single-owner SaaS admin model
- No access to user private content
- Capabilities:
  - Manage AI personas & system prompts
  - Monitor system health
  - Control orchestration rules

---

## 🧩 Key Design Decisions

- ❌ No streaks
- ❌ No session mode
- ❌ No guilt-based reminders
- ✅ Calendar as the notification layer
- ✅ Recovery > punishment
- ✅ Persona stability + adaptive intensity

---

## 🛠️ Tech Stack

- Laravel 12
- Blade
- Tailwind CSS
- MySQL
- Google OAuth
- Google Calendar API
- AI (task planning & feedback)

---

## 🚧 Project Status

- ✅ Auth (Email + Google)
- ✅ Google Calendar sync
- ✅ Daily Check-In system
- ✅ Recovery classification & planning
- ✅ Persona management (admin)

---

## 🎯 Vision

AccoSpark is not a productivity app.

It’s a **behavior-aware system** designed to:
- Reduce silent failure
- Encourage recovery
- Respect real-world constraints
- Scale from solo users to SaaS

---

> “The system should not break when the user does.”
        