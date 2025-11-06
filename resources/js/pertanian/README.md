# Pertanian JS Modules

This folder contains the modularized frontend logic for Unified Pertanian Reports (benih-pupuk, lahan, iklim-opt-dpi) and the chatbot.

Key goals:
- Keep routes, Livewire wiring, and API contracts intact.
- Reduce a single large Alpine component into composable pieces (API, utils, UI controllers, chatbot) while preserving the public interface used by Blade: `x-data="pertanianReportForm({ ... })"`.

Structure:
- index.js — compatibility factory composing the legacy Alpine ctx with modular controllers.
- api/
  - http.js — fetch wrapper with CSRF, optional cache, and centralized parseJsonOrText.
  - pertanianApi.js — topiks, variabels, klasifikasis, years, bulans, wilayahs, filter endpoints.
  - chatbotApi.js — send and reset messages for `/api/chatbot`.
- utils/
  - dom.js — `getCsrfToken`, `sanitizeHtml`, `scrollToBottom`, `setupResizeObserver`.
  - format.js — simple number and timestamp formatting for Indonesian locale.
  - http.js — `parseJsonOrText(res)` used across API and UI.
- data/
  - loaders.js — `ensureModuleData`, `ensureVariabels`, `ensureKlasifikasis`, `ensureWilayahs`.
- ui/
  - layout.js — layout height sync helpers.
  - results.js — data fetching, stored results management.
  - chart.js — chart rendering, legend and province scroll helpers.
  - export.js — Excel export trigger wiring.
- chatbot/
  - conversation.js — render bubbles, switch modes, reset flow.
  - guidedWizard.js — topik/variabel/klasifikasi/year/month/wilayah prompts.
  - wizardUi.js — month checklist and checklist interactions.
  - handlers.js — guided option handler and stepBack.
  - router.js — message routing: natural/structured/guided and rePromptCurrentStep.
  - structuredBridge.js — apply structured suggestion and finish preview.
  - preview.js — present preview (summary/table) and helpers.
  - utils.js — summary/tutor text builders.
  - quickStart.js — Quick Start flow (`runQuickStart`).

Public contract (unchanged):
- Alpine component remains registered as `pertanianReportForm` from `resources/js/app.js`.
- Blade templates continue to use `x-data="pertanianReportForm({ moduleType, initialData })"`.
- Chart.js remains loaded via CDN and referenced as global `Chart`.

Legacy component status (`resources/js/components/pertanianReportForm.js`):
- Purpose: retain state shape and public method names for Blade/Alpine.
- Most methods are delegated via the factory to modular controllers. The legacy file now primarily contains:
  - State (selection, wizard, chat), watchers, and computed getters.
  - Minimal stubs for methods that are overridden in the factory.
- Notable changes:
  - JSON/text parsing is centralized in `utils/http.parseJsonOrText` and exposed as `ctx.parseJsonOrText`.
  - Chat routing is handled by `chatbot/router.js` (natural/structured/guided/rePromptCurrentStep).
  - Quick Start moved to `chatbot/quickStart.js`.
  - Checklist interactions moved to `chatbot/wizardUi.js`.
  - Conversation basics (`renderBotText`, `switchChatMode`, reset) come from `chatbot/conversation.js`.
  - Data loaders, results, charts, and export come from their respective modules.
