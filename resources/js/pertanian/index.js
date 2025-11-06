// Phase 2: Compatibility factory
// Keep existing public interface while preparing for modular composition in next phases.

import legacyPertanianReportForm from '../components/pertanianReportForm.js';
import * as layout from './ui/layout.js';
import * as loaders from './data/loaders.js';
import { getCsrfToken, sanitizeHtml, scrollToBottom } from './utils/dom.js';
import * as results from './ui/results.js';
import * as chart from './ui/chart.js';
import * as form from './ui/form.js';
import * as wilayah from './ui/wilayah.js';
import * as convo from './chatbot/conversation.js';
import * as wizard from './chatbot/guidedWizard.js';
import * as bridge from './chatbot/structuredBridge.js';
import * as chatUtils from './chatbot/utils.js';
import * as preview from './chatbot/preview.js';
import * as handlers from './chatbot/handlers.js';
import * as wizardUi from './chatbot/wizardUi.js';
import * as exporter from './ui/export.js';
import * as router from './chatbot/router.js';
import { parseJsonOrText } from './utils/http.js';
import * as quickStart from './chatbot/quickStart.js';
import * as quickStartTemplates from './chatbot/quickStartTemplates.js';

// For now, just delegate to the legacy implementation.
// In Phase 3+, this file will compose state + controllers (api, ui, chatbot) and return the Alpine data object.
export default function pertanianReportForm(config) {
  // Build legacy Alpine context
  const ctx = legacyPertanianReportForm(config);

  // Phase 3: safely override selected methods to delegate to modular implementations
  // DOM/utility methods
  ctx.getCsrfToken = function() { return getCsrfToken(); };
  ctx.sanitizeHtml = function(input) { return sanitizeHtml(input); };
  ctx.scrollChatToBottom = function() { try { scrollToBottom(this.$refs?.chatScroll); } catch {} };
  ctx.parseJsonOrText = function(res) { return parseJsonOrText(res); };

  // Layout syncing
  ctx.setupHeightSync = function() { return layout.setupHeightSync(this); };
  ctx.syncHeights = function() { return layout.syncHeights(this); };

  // Data loaders
  ctx.ensureModuleData = function(module) { return loaders.ensureModuleData(this, module); };
  ctx.ensureVariabels = function(module, topikId) { return loaders.ensureVariabels(this, module, topikId); };
  ctx.ensureKlasifikasis = function(module, variabelId) { return loaders.ensureKlasifikasis(this, module, variabelId); };
  ctx.ensureWilayahs = function() { return loaders.ensureWilayahs(this); };

  // Results & filtering
  ctx.fetchData = function() { return results.fetchData(this); };
  ctx.saveWizardResult = function(chat) { return results.saveWizardResult(this, chat); };
  ctx.selectStoredResult = function(index) { return results.selectStoredResult(this, index); };
  ctx.toggleResultSelection = function(id, checked) { return results.toggleResultSelection(this, id, checked); };
  ctx.removeSelectedResults = function() { return results.removeSelectedResults(this); };
  ctx.clearAllResults = function() { return results.clearAllResults(this); };
  ctx.clearAllResultsConfirmed = function() { return results.clearAllResultsConfirmed(this); };
  ctx.computeDynamicRows = function() { return results.computeDynamicRows(this); };

  // Charts
  ctx.renderChart = function() { return chart.renderChart(this); };
  ctx.toggleLegend = function() { return chart.toggleLegend(this); };
  ctx.scrollToProvince = function() { return chart.scrollToProvince(this); };
  ctx.initChartResizeHandlerOnce = function() { return chart.initChartResizeHandlerOnce(this); };

  // Chat conversation basics
  ctx.renderBotText = function(text) { return convo.renderBotText(this, text); };
  ctx.switchChatMode = function(mode, opts) { return convo.switchChatMode(this, mode, opts || {}); };
  ctx.resetGuidedChat = function() { return convo.resetGuidedChat(this); };
  ctx.startGuidedInline = function() { return convo.startGuidedInline(this); };
  ctx.openResetConfirm = function() { return convo.openResetConfirm(this); };
  ctx.cancelResetConfirm = function() { return convo.cancelResetConfirm(this); };
  ctx.confirmReset = function() { return convo.confirmReset(this); };

  // Guided wizard prompt renderers
  ctx.loadWizardTopiks = function() { return wizard.loadWizardTopiks(this); };
  ctx.loadWizardVariabels = function() { return wizard.loadWizardVariabels(this); };
  ctx.loadWizardKlasifikasis = function() { return wizard.loadWizardKlasifikasis(this); };
  ctx.askYears = function() { return wizard.askYears(this); };
  ctx.loadWizardBulans = function() { return wizard.loadWizardBulans(this); };
  ctx.askWilayah = function() { return wizard.askWilayah(this); };
  ctx.askProvinces = function(single = false) { return wizard.askProvinces(this, single); };
  ctx.askKabupaten = function(provId) { return wizard.askKabupaten(this, provId); };
  ctx.renderBulanChecklist = function() { return wizardUi.renderBulanChecklist(this); };
  ctx.toggleChecklist = function(chat, value) { return wizardUi.toggleChecklist(this, chat, value); };
  ctx.clearChecklist = function(chat) { return wizardUi.clearChecklist(this, chat); };
  ctx.confirmChecklist = function(index) { return wizardUi.confirmChecklist(this, index); };

  // Structured bridge & preview
  ctx.applyStructuredSuggestion = function(moduleChoice, opts) { return bridge.applyStructuredSuggestion(this, moduleChoice, opts || {}); };
  ctx.finishPreview = function() { return bridge.finishPreview(this); };

  // Preview and utils
  ctx.presentPreview = function(style) { return preview.presentPreview(this, style); };
  ctx.showAsTable = function(chat) { return preview.showAsTable(this, chat); };
  ctx.buildSummaryLines = function(tableData) { return chatUtils.buildSummaryLines(tableData); };
  ctx.buildTutorialText = function(moduleSlug, pend) { return chatUtils.buildTutorialText(moduleSlug, pend); };
  ctx.exportExcel = function() { return exporter.exportExcel(this); };
  ctx.runQuickStart = function(templateId) { return quickStart.runQuickStart(this, templateId); };
  ctx.getQuickStartTemplates = function() { return quickStartTemplates.getQuickStartTemplates(this); };

  // Chatbot handlers
  ctx.stepBack = function() { return handlers.stepBack(this); };
  ctx.handleOption = function(index, opt) { return handlers.handleOption(this, index, opt); };

  // Chatbot message routing
  ctx.rePromptCurrentStep = function() { return router.rePromptCurrentStep(this); };
  ctx.handleNaturalMessage = function(messageToSend) { return router.handleNaturalMessage(this, messageToSend); };
  ctx.handleStructuredMessage = function(messageToSend) { return router.handleStructuredMessage(this, messageToSend); };
  ctx.handleGuidedFlow = function(messageToSend) { return router.handleGuidedFlow(this, messageToSend); };
  ctx.sendMessage = function() { return router.sendMessage(this); };

  // Form selection & wilayah helpers
  ctx.selectTopik = function(id) { return form.selectTopik(this, id); };
  ctx.selectVariabel = function(id) { return form.selectVariabel(this, id); };
  ctx.isSelectionValid = function() { return form.isSelectionValid(this); };
  ctx.addSelection = function() { return form.addSelection(this); };
  ctx.removeSelection = function() { return form.removeSelection(this); };
  ctx.resetSelection = function() { return form.resetSelection(this); };
  ctx.resetForm = function() { return form.resetForm(this); };
  ctx.loadVariabels = function() { return form.loadVariabels(this); };
  ctx.loadKlasifikasis = function() { return form.loadKlasifikasis(this); };

  ctx.toggleKabupaten = function(id) { return wilayah.toggleKabupaten(this, id); };
  ctx.selectAllKabupatenInSelectedProvinsi = function() { return wilayah.selectAllKabupatenInSelectedProvinsi(this); };
  ctx.clearKabupatenInSelectedProvinsi = function() { return wilayah.clearKabupatenInSelectedProvinsi(this); };
  ctx.toggleWilayah = function(id) { return wilayah.toggleWilayah(this, id); };

  return ctx;
}
