export default function pertanianReportForm(config) {
            return {
                moduleType: config.moduleType,
                allData: config.initialData || { topiks: [], variabels: [], klasifikasis: [], tahuns: [], bulans: [], wilayahs: [] },

                init() {
                    // Use layout helpers wired via factory for watcher side-effects
                    this.$watch('wilayahLevel', () => { try { this.onWilayahLevelChanged && this.onWilayahLevelChanged(); } catch {} });
                    this.$watch('selectedProvinsiId', () => { try { this.onSelectedProvinsiChanged && this.onSelectedProvinsiChanged(); } catch {} });

                    // Auto-scroll chat on open and when messages change
                    this.$watch('chatOpen', (open) => {
                        if (open) { try { this.onChatOpen && this.onChatOpen(); } catch {} }
                    });
                    this.$watch('conversation', () => { this.$nextTick(() => this.scrollChatToBottom()); });

                    // Warn user before reload/close if there are stored results
                    try { this.setupBeforeUnloadGuard && this.setupBeforeUnloadGuard(); } catch {}

                    // Equalize Wilayah container height with layout container
                    this.$nextTick(() => this.setupHeightSync());

                    // Do not auto-start guided here; onboarding handled when chat opens
                },

                // Form state
                selection: {
                    topik_id: null,
                    variabel_id: null,
                    klasifikasi_ids: [],
                    tahun_ids: [],
                    bulan_ids: [],
                    provinsi_ids: [],
                    kabupaten_ids: [],
                    tata_letak: 'tipe_1',
                    wilayah: { selected_provinsi: null },
                },

                wilayahLevel: 'nasional', // 'nasional' or 'provinsi'
                selectedProvinsiId: null,

                // Search inputs
                search: { topik: '', variabel: '', klasifikasi: '', tahun: '', bulan: '', wilayah: '' },

                // UI state
                isProcessing: false,
                activeTab: 'tabel',
                activeResultTab: 'tabel',
                selections: [],
                selectedForRemoval: [],
                searchResults: { headers: [], rows: [], config: {} },
                storedResults: [],
                selectedResultIndex: null,
                // ChatBot state
                chatOpen: false,
                // Chat mode routing: 'natural' | 'structured' | 'guided'
                chatMode: 'natural',
                // Lock typing when guided flow is active
                guidedLock: false,
                // One-time onboarding: show guided wizard only once when chat is opened
                didOnboardingGuided: false,
                // Prevent repeated guided fallback on unknown intent
                guidedFallbackShown: false,
                isLoading: false,
                userMessage: '',
                conversation: [],
                // When natural reply contains structured suggestion, hold it until user confirms via text
                pendingStructured: false,
                // Pending typed clarifications (no bubbles), e.g., { type:'klasifikasi', candidates:[{id,nama}], module, variableId }
                pendingPrompt: null,
                showChatResetConfirm: false,
                // Compact mode: minimize bubbles, avoid option prompts in natural/structured
                compactChat: true,
                // Help modal visibility
                showHelp: false,
                // Help modal active tab (persist while page active)
                helpTab: 'examples',
                // Structured search integration state
                structuredSuggestion: null,
                useStructuredAfterModule: false,
                // Guided flow context
                wizard: {
                    step: 'module', // module -> topik -> variabel -> klasifikasi -> waktu -> wilayah -> preview
                    moduleType: null,
                    topikId: null,
                    variabelId: null,
                    klasifikasiIds: [],
                    tahunIds: [],
                    bulanIds: [],
                    provinsiIds: [],
                    kabupatenIds: [],
                },
                wizardData: {
                    topiks: config.initialData?.topiks || [],
                    // variabels may be loaded per topik
                    variabelsByTopik: {},
                    klasifikasisByVariabel: {},
                    years: config.initialData?.tahuns || [],
                    bulans: config.initialData?.bulans || [],
                    wilayahs: config.initialData?.wilayahs || [],
                },
                // Result management
                selectedResultIds: [],
                showClearConfirm: false,
                // Grafik helpers
                selectedProvinceForScroll: null,
                showLegend: false,
                // Control beforeunload prompt during safe actions (e.g., export)
                skipUnloadPrompt: false,

            // Quick Start templates are provided by factory (chatbot/quickStartTemplates.getQuickStartTemplates)
            getQuickStartTemplates() { /* overridden by factory (chatbot/quickStartTemplates.getQuickStartTemplates) */ return []; },

            // Height sync helpers (overridden by factory)
            setupHeightSync() { /* overridden by factory (ui/layout.setupHeightSync) */ },
            syncHeights() { /* overridden by factory (ui/layout.syncHeights) */ },


            // Chat scrolling helper (overridden by factory)
            scrollChatToBottom() { /* overridden by factory (utils/dom.scrollToBottom) */ },
            // DOM/utility helpers (overridden by factory)
            getCsrfToken() { /* overridden by factory (utils/dom.getCsrfToken) */ return null; },
            sanitizeHtml(input) { /* overridden by factory (utils/dom.sanitizeHtml) */ return String(input || ''); },
            // Guided chatbot basics (all overridden by factory/conversation.js)
            resetGuidedChat() { /* overridden by factory (chatbot/conversation.resetGuidedChat) */ },
            openResetConfirm() { /* overridden by factory (chatbot/conversation.openResetConfirm) */ },
            cancelResetConfirm() { /* overridden by factory (chatbot/conversation.cancelResetConfirm) */ },
            async confirmReset() { /* overridden by factory (chatbot/conversation.confirmReset) */ },
            endGuidedFlow() { /* overridden by factory (chatbot/conversation.endGuidedFlow) */ },
            // Start guided flow inline (overridden by factory)
            startGuidedInline() { /* overridden by factory (chatbot/conversation.startGuidedInline) */ },
            // Go back one guided step and re-render the prompt
            async stepBack() { /* overridden by factory (chatbot/handlers.stepBack) */ },
            // Guided chat option handlers
            async handleOption(index, opt) { /* overridden by factory (chatbot/handlers.handleOption) */ },
            renderBulanChecklist() { /* overridden by factory (chatbot/wizardUi.renderBulanChecklist) */ },
            // Helpers for fetch and sanitizer are provided by factory (utils/dom)
            getCsrfToken() { /* overridden by factory (utils/dom.getCsrfToken) */ return ''; },
            sanitizeHtml(input) { /* overridden by factory (utils/dom.sanitizeHtml) */ return String(input || ''); },
            async parseJsonOrText(res) { /* overridden by factory (utils/http.parseJsonOrText) */ },
            async applyStructuredSuggestion(moduleChoice, options = {}) { /* overridden by factory (chatbot/structuredBridge.applyStructuredSuggestion) */ },
            async runQuickStart(templateId) { /* overridden by factory (chatbot/quickStart.runQuickStart) */ },
            toggleChecklist(chat, value) { /* overridden by factory (chatbot/wizardUi.toggleChecklist) */ },
            clearChecklist(chat) { /* overridden by factory (chatbot/wizardUi.clearChecklist) */ },
            confirmChecklist(index) { /* overridden by factory (chatbot/wizardUi.confirmChecklist) */ },
            // Wizard data loaders and prompts
            loadWizardTopiks() { /* overridden by factory (chatbot/guidedWizard.loadWizardTopiks) */ },
            loadWizardVariabels() { /* overridden by factory (chatbot/guidedWizard.loadWizardVariabels) */ },
            loadWizardKlasifikasis() { /* overridden by factory (chatbot/guidedWizard.loadWizardKlasifikasis) */ },
            askYears() { /* overridden by factory (chatbot/guidedWizard.askYears) */ },
            loadWizardBulans() { /* overridden by factory (chatbot/guidedWizard.loadWizardBulans) */ },
            askWilayah() { /* overridden by factory (chatbot/guidedWizard.askWilayah) */ },
            askProvinces(single=false) { /* overridden by factory (chatbot/guidedWizard.askProvinces) */ },
            askKabupaten(provId) { /* overridden by factory (chatbot/guidedWizard.askKabupaten) */ },
            async ensureModuleData(module){ /* overridden by factory (data/loaders.ensureModuleData) */ },
            async ensureVariabels(module, topikId){ /* overridden by factory (data/loaders.ensureVariabels) */ },
            async ensureKlasifikasis(module, variabelId){ /* overridden by factory (data/loaders.ensureKlasifikasis) */ },
            async ensureWilayahs(){ /* overridden by factory (data/loaders.ensureWilayahs) */ },
            async finishPreview() { /* overridden by factory (chatbot/structuredBridge.finishPreview) */ },
            // After choosing a preview style, render it
            presentPreview(style) { /* overridden by factory (chatbot/preview.presentPreview) */ },
            showAsTable(chat) { /* overridden by factory (chatbot/preview.showAsTable) */ },
            buildSummaryLines(tableData) { /* overridden by factory (chatbot/utils.buildSummaryLines) */ },
            buildTutorialText(moduleSlug, pend) { /* overridden by factory (chatbot/utils.buildTutorialText) */ },
            saveWizardResult(chat) { /* overridden by factory (ui/results.saveWizardResult) */ },
            
                // Methods
                selectTopik(id) { /* overridden by factory (ui/form.selectTopik) */ },
                selectVariabel(id) { /* overridden by factory (ui/form.selectVariabel) */ },
                isSelectionValid() { /* overridden by factory (ui/form.isSelectionValid) */ return false; },
                addSelection() { /* overridden by factory (ui/form.addSelection) */ },
                removeSelection() { /* overridden by factory (ui/form.removeSelection) */ },
                resetSelection() { /* overridden by factory (ui/form.resetSelection) */ },
                resetForm() { /* overridden by factory (ui/form.resetForm) */ },

                selectStoredResult(index) { /* overridden by factory (ui/results.selectStoredResult) */ },
                toggleResultSelection(id, checked) { /* overridden by factory (ui/results.toggleResultSelection) */ },
                removeSelectedResults() { /* overridden by factory (ui/results.removeSelectedResults) */ },
                clearAllResults() { /* overridden by factory (ui/results.clearAllResults) */ },
                clearAllResultsConfirmed() { /* overridden by factory (ui/results.clearAllResultsConfirmed) */ },

                async ensureModuleData(module){ /* overridden by factory (data/loaders.ensureModuleData) */ },
                async ensureVariabels(module, topikId){ /* overridden by factory (data/loaders.ensureVariabels) */ },
                async ensureKlasifikasis(module, variabelId){ /* overridden by factory (data/loaders.ensureKlasifikasis) */ },
                async ensureWilayahs(){ /* overridden by factory (data/loaders.ensureWilayahs) */ },
                async fetchData(){ /* overridden by factory (ui/results.fetchData) */ },
                exportExcel() { /* overridden by factory (ui/export.exportExcel) */ },

                // Dependent selection reset
                loadVariabels() { /* overridden by factory (ui/form.loadVariabels) */ },
                loadKlasifikasis() { /* overridden by factory (ui/form.loadKlasifikasis) */ },

                // Computed properties for filtering dropdowns
                get filteredTopik() { return this.allData.topiks; },
                get filteredVariabel() { if (!this.selection.topik_id) return []; return this.allData.variabels.filter(v => String(v.topik_id) === String(this.selection.topik_id)); },
                get filteredKlasifikasi() { if (!this.selection.variabel_id) return []; return this.allData.klasifikasis.filter(k => String(k.variabel_id) === String(this.selection.variabel_id)); },
                get filteredTahun() { return this.allData.tahuns.filter(t => t.toString().includes(this.search.tahun)); },
                get filteredBulan() { return this.allData.bulans.filter(b => b.nama.toLowerCase().includes(this.search.bulan.toLowerCase())); },
                get filteredWilayah() {
                    const provinces = Array.isArray(this.allData.wilayahs) ? this.allData.wilayahs : [];
                    const term = (this.search.wilayah || '').toLowerCase().trim();
                    if (this.wilayahLevel === 'nasional') {
                        // Filter provinces by name
                        if (!term) return provinces;
                        return provinces.filter(p => (p.nama || '').toLowerCase().includes(term));
                    }
                    // Provinsi level: keep all provinces, but filter kabupaten list of each province by term
                    return provinces.map(p => {
                        const kab = Array.isArray(p.kabupaten) ? p.kabupaten : [];
                        const filteredKab = term ? kab.filter(k => (k.nama || '').toLowerCase().includes(term)) : kab;
                        return { ...p, kabupaten: filteredKab };
                    });
                },

                toggleKabupaten(id) { /* overridden by factory (ui/wilayah.toggleKabupaten) */ },
                selectAllKabupatenInSelectedProvinsi() { /* overridden by factory (ui/wilayah.selectAllKabupatenInSelectedProvinsi) */ },
                clearKabupatenInSelectedProvinsi() { /* overridden by factory (ui/wilayah.clearKabupatenInSelectedProvinsi) */ },
                toggleWilayah(id) { /* overridden by factory (ui/wilayah.toggleWilayah) */ },

                // Aliases to server-provided structures
                get dynamicHeaders() {
                    const currentResult = this.selectedResultIndex !== null ? this.storedResults[this.selectedResultIndex] : null;
                    return currentResult?.results?.headers || [];
                },
                get dynamicRows() {
                    // Delegate to centralized sorter in ui/results
                    try { return this.computeDynamicRows ? this.computeDynamicRows() : []; } catch { return []; }
                },

                renderChart() { /* overridden by factory (ui/chart.renderChart) */ },
                toggleLegend() { /* overridden by factory (ui/chart.toggleLegend) */ },
                scrollToProvince() { /* overridden by factory (ui/chart.scrollToProvince) */ },
                initChartResizeHandlerOnce: (function() { let initialized = false; return function() { if (!initialized) { initialized = true; /* overridden by factory (ui/chart.initChartResizeHandlerOnce) */ } }; })(),
                
                // ChatBot Methods (delegated via factory)
                renderBotText(text) { /* overridden by factory (chatbot/conversation.renderBotText) */ },
                switchChatMode(mode, { silent = false } = {}) { /* overridden by factory (chatbot/conversation.switchChatMode) */ },
                async rePromptCurrentStep() { /* overridden by factory (chatbot/router.rePromptCurrentStep) */ },
                async handleNaturalMessage(messageToSend) { /* overridden by factory (chatbot/router.handleNaturalMessage) */ },
                async handleStructuredMessage(messageToSend) { /* overridden by factory (chatbot/router.handleStructuredMessage) */ },
                async handleGuidedFlow(messageToSend) { /* overridden by factory (chatbot/router.handleGuidedFlow) */ },
                async sendMessage() { /* overridden by factory (chatbot/router.sendMessage) */ }
            };
}