export default function pertanianReportForm(config) {
            return {
                moduleType: config.moduleType,
                allData: config.initialData || { topiks: [], variabels: [], klasifikasis: [], tahuns: [], bulans: [], wilayahs: [] },

                init() {
                    this.$watch('wilayahLevel', () => {
                        this.selectedProvinsiId = null;
                        this.$nextTick(() => this.syncHeights());
                    });
                    this.$watch('selectedProvinsiId', () => {
                        this.selection.kabupaten_ids = [];
                        this.$nextTick(() => this.syncHeights());
                    });

                    // Auto-scroll chat on open and when messages change
                    this.$watch('chatOpen', (open) => {
                        if (open) {
                            // Onboarding: show guided wizard only once when the chat is first opened
                            if (!this.didOnboardingGuided) {
                                this.switchChatMode('guided', { silent: true });
                                this.resetGuidedChat();
                                this.didOnboardingGuided = true;
                            }
                            this.$nextTick(() => this.scrollChatToBottom());
                        }
                    });
                    this.$watch('conversation', () => { this.$nextTick(() => this.scrollChatToBottom()); });

                    // Warn user before reload/close if there are stored results
                    window.addEventListener('beforeunload', (e) => {
                        try {
                            if (!this.skipUnloadPrompt && this.storedResults && this.storedResults.length > 0) {
                                e.preventDefault();
                                e.returnValue = '';
                            }
                        } catch (_) { /* noop */ }
                    });

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

                // TODO(next): Enhanced incomplete query prompting
                // - If user asks like "data benih jagung 2024 di jawa tengah":
                //   1) Detect module benih-pupuk, variable family contains "jagung"
                //   2) Ask: gunakan semua bulan atau pilih bulan tertentu? (implemented via waktu_bulan_choice)
                //   3) Ask: klasifikasi apa yang diinginkan? tampilkan checklist dari variabel terpilih
                //   4) Ask: tingkat wilayah provinsi Jawa Tengah saja, atau kabupaten tertentu? (offer provinsi vs kabupaten)
                //   5) Proceed to preview when choices completed.

                // Quick Start templates for guided flow
                getQuickStartTemplates() {
                    return [
                        { id:'qs_pupuk_urea_latest', label:'Mulai Cepat: Pupuk Urea (nasional, tahun terbaru)', module:'benih-pupuk', topikMatch:'pupuk', variabelMatches:['urea'], pick:{ years:'latest', months:'all', wilayah:'top5prov', klasifikasi:'few' } },
                        { id:'qs_pupuk_npk_latest', label:'Mulai Cepat: Pupuk NPK (nasional, tahun terbaru)', module:'benih-pupuk', topikMatch:'pupuk', variabelMatches:['npk'], pick:{ years:'latest', months:'all', wilayah:'top5prov', klasifikasi:'few' } },
                        { id:'qs_lahan_latest', label:'Mulai Cepat: Lahan (nasional, tahun terbaru)', module:'lahan', topikMatch:'lahan', variabelMatches:['luas','lahan','total'], pick:{ years:'latest', wilayah:'top5prov', klasifikasi:'few' } },
                        { id:'qs_iklim_hujan_latest', label:'Mulai Cepat: Curah Hujan (nasional, tahun terbaru)', module:'iklim-opt-dpi', topikMatch:'hujan', variabelMatches:['curah','hujan'], pick:{ years:'latest', months:'all', wilayah:'top5prov', klasifikasi:'few' } },
                    ];
                },

                // Height sync helpers
                setupHeightSync() {
                    try {
                        const layoutEl = this.$refs.layoutBox;
                        if (!layoutEl) return;
                        const ro = new ResizeObserver(() => this.syncHeights());
                        ro.observe(layoutEl);
                        window.addEventListener('resize', () => this.syncHeights());
                        this.syncHeights();
                    } catch (_) { /* noop */ }
                },
                syncHeights() {
                    try {
                        const layoutEl = this.$refs.layoutBox;
                        const wilayahEl = this.$refs.wilayahBox;
                        if (!layoutEl || !wilayahEl) return;
                        const target = layoutEl.offsetHeight;
                        wilayahEl.style.height = target + 'px';
                        // adjust all scroll areas inside wilayah
                        const scrollEls = wilayahEl.querySelectorAll('[data-wilayah-scroll]');
                        const contRect = wilayahEl.getBoundingClientRect();
                        const styles = getComputedStyle(wilayahEl);
                        const padB = parseFloat(styles.paddingBottom || '0');
                        scrollEls.forEach((scrollEl) => {
                            const scrollRect = scrollEl.getBoundingClientRect();
                            const topOffset = scrollRect.top - contRect.top;
                            const desired = Math.max(120, target - topOffset - padB);
                            scrollEl.style.height = desired + 'px';
                            scrollEl.style.maxHeight = desired + 'px';
                            scrollEl.style.overflowY = 'auto';
                        });
                    } catch (_) { /* noop */ }
                },


            // Chat scrolling helper
            scrollChatToBottom() {
                try {
                    const el = this.$refs.chatScroll;
                    if (!el) return;
                    el.scrollTop = el.scrollHeight;
                } catch (_) { /* noop */ }
            },
            // Reset guided chatbot
            resetGuidedChat() {
                this.wizard = { step: 'module', moduleType: null, topikId: null, variabelId: null, klasifikasiIds: [], tahunIds: [], bulanIds: [], provinsiIds: [], kabupatenIds: [] };
                this.conversation = [
                    { sender: 'bot', type: 'text', text: this.sanitizeHtml('Saya bisa bantu mencari data <strong>Lahan</strong>, <strong>Benih & Pupuk</strong>, atau <strong>Iklim & OPT DPI</strong>. Mau mulai dari modulnya?') },
                    { sender: 'bot', type: 'options', title: 'Pilih Modul', options: [
                        { value: 'benih-pupuk', label: 'Benih & Pupuk' },
                        { value: 'lahan', label: 'Lahan' },
                        { value: 'iklim-opt-dpi', label: 'Iklim & OPT DPI' },
                    ]}
                ];
                // Quick Start intents bubble
                try {
                    const quickStartOpts = (this.getQuickStartTemplates() || []).map(t => ({ value:t.id, label:t.label, quickStart:true, templateId:t.id, scope:'quickstart' }));
                    if (quickStartOpts.length) {
                        this.conversation.push({ sender:'bot', type:'options', title:'Mulai Cepat', options: quickStartOpts });
                    }
                } catch(_) { /* noop */ }
                this.$nextTick(()=>this.scrollChatToBottom());
            },
            openResetConfirm(){ this.showChatResetConfirm = true; },
            cancelResetConfirm(){ this.showChatResetConfirm = false; },
            async confirmReset(){
                try {
                    await fetch('/api/chatbot/reset', { method:'POST', headers:{ 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') } });
                } catch(_) { /* ignore network errors for UX */ }
                this.resetGuidedChat();
                this.userMessage = '';
                this.showChatResetConfirm = false;
            },
            // Start guided flow inline without clearing previous conversation
            startGuidedInline() {
                this.wizard = { step: 'module', moduleType: null, topikId: null, variabelId: null, klasifikasiIds: [], tahunIds: [], bulanIds: [], provinsiIds: [], kabupatenIds: [] };
                this.conversation.push({ sender:'bot', type:'text', text:'Silahkan Pilih Modul lagi untuk melanjutkan' });
                this.conversation.push({ sender: 'bot', type: 'options', title: 'Pilih Modul', options: [
                    { value: 'benih-pupuk', label: 'Benih & Pupuk' },
                    { value: 'lahan', label: 'Lahan' },
                    { value: 'iklim-opt-dpi', label: 'Iklim & OPT DPI' },
                ]});
                this.$nextTick(()=>this.scrollChatToBottom());
            },
            // Go back one guided step and re-render the prompt
            async stepBack() {
                const step = this.wizard.step;
                // Determine previous logical step
                const prevMap = {
                    'topik': 'module',
                    'variabel': 'topik',
                    'klasifikasi': 'variabel',
                    'waktu_tahun': 'klasifikasi',
                    'waktu_bulan': 'waktu_tahun',
                    'wilayah_level': (this.wizard.moduleType === 'lahan' ? 'waktu_tahun' : 'waktu_bulan'),
                    'wilayah_provinsi': 'wilayah_level',
                    'wilayah_pilih_provinsi': 'wilayah_level',
                    'wilayah_kabupaten': 'wilayah_pilih_provinsi',
                        'choose_preview_style': 'wilayah_level',
                };
                const prev = prevMap[step] || 'module';
                this.wizard.step = prev;
                // Render the corresponding prompt again
                if (prev === 'module') {
                    this.conversation.push({ sender:'bot', type:'options', title:'Pilih Modul', options:[
                        { value:'benih-pupuk', label:'Benih & Pupuk' },
                        { value:'lahan', label:'Lahan' },
                        { value:'iklim-opt-dpi', label:'Iklim & OPT DPI' },
                    ]});
                } else if (prev === 'topik') {
                    await this.ensureModuleData(this.wizard.moduleType || this.moduleType);
                    this.loadWizardTopiks();
                } else if (prev === 'variabel') {
                    await this.ensureVariabels(this.wizard.moduleType || this.moduleType, this.wizard.topikId);
                    this.loadWizardVariabels();
                } else if (prev === 'klasifikasi') {
                    await this.ensureKlasifikasis(this.wizard.moduleType || this.moduleType, this.wizard.variabelId);
                    this.loadWizardKlasifikasis();
                } else if (prev === 'waktu_tahun') {
                    this.askYears();
                } else if (prev === 'waktu_bulan') {
                    this.loadWizardBulans();
                } else if (prev === 'wilayah_level') {
                    this.askWilayah();
                } else if (prev === 'wilayah_provinsi') {
                    await this.ensureWilayahs();
                    this.askProvinces();
                } else if (prev === 'wilayah_pilih_provinsi') {
                    await this.ensureWilayahs();
                    this.askProvinces(true);
                } else if (prev === 'wilayah_kabupaten') {
                    await this.ensureWilayahs();
                    if (this.wizard.provinsiIds && this.wizard.provinsiIds.length) {
                        this.askKabupaten(this.wizard.provinsiIds[0]);
                    } else if (this.selectedProvinsiId) {
                        this.askKabupaten(this.selectedProvinsiId);
                    }
                }
                this.$nextTick(()=>this.scrollChatToBottom());
            },
            // Guided chat option handlers
            async handleOption(index, opt) {
                // Handle Quick Start templates regardless of current step
                if (opt && opt.scope === 'quickstart' && opt.quickStart) {
                    this.conversation.push({ sender:'user', type:'text', text: opt.label });
                    await this.runQuickStart(opt.templateId);
                    this.$nextTick(()=>this.scrollChatToBottom());
                    return;
                }
                // Handle structured search decision buttons
                if (opt && opt.value === 'use_structured' && this.structuredSuggestion) {
                    // User picked to use structured result
                    this.conversation.push({ sender:'user', type:'text', text: opt.label || 'Gunakan hasil terstruktur' });
                    // NEW: add a natural confirmation bubble so the flow feels human
                    // This acknowledges the user's choice before heavy processing starts
                    this.conversation.push({ sender:'bot', type:'text', text:'Baik, sedang saya ambilkan datanya...' });
                    this.$nextTick(()=>this.scrollChatToBottom());
                    await this.applyStructuredSuggestion(null);
                    this.$nextTick(()=>this.scrollChatToBottom());
                    return;
                }
                if (opt && opt.value === 'skip_structured' && this.structuredSuggestion) {
                    this.conversation.push({ sender:'user', type:'text', text: opt.label || 'Lanjutkan dengan chatbot' });
                    this.conversation.push({ sender:'bot', type:'text', text:'Baik, lanjutkan dengan chatbot. Silakan ketik pertanyaan berikutnya.' });
                    this.structuredSuggestion = null;
                    this.useStructuredAfterModule = false;
                    this.$nextTick(()=>this.scrollChatToBottom());
                    return;
                }
                // If user is choosing module from structured suggestions
                if (this.useStructuredAfterModule && opt && opt.value && ['lahan','benih-pupuk','iklim-opt-dpi'].includes(opt.value)) {
                    // User chose a specific module stemming from structured suggestion
                    this.conversation.push({ sender:'user', type:'text', text: opt.label || opt.value });
                    // NEW: confirm naturally before applying the structured plan
                    this.conversation.push({ sender:'bot', type:'text', text:'Baik, sedang saya ambilkan datanya...' });
                    this.$nextTick(()=>this.scrollChatToBottom());
                    await this.applyStructuredSuggestion(opt.value);
                    this.useStructuredAfterModule = false;
                    this.$nextTick(()=>this.scrollChatToBottom());
                    return;
                }
                const step = this.wizard.step;
                if (step === 'module') {
                    this.wizard.moduleType = opt.value;
                    this.wizard.step = 'topik';
                    this.conversation.push({ sender: 'user', type: 'text', text: opt.label });
                    await this.ensureModuleData(this.wizard.moduleType);
                    this.loadWizardTopiks();
                } else if (step === 'topik') {
                    this.wizard.topikId = opt.value;
                    this.wizard.step = 'variabel';
                    this.conversation.push({ sender: 'user', type: 'text', text: opt.label });
                    await this.ensureVariabels(this.wizard.moduleType, this.wizard.topikId);
                    this.loadWizardVariabels();
                } else if (step === 'variabel') {
                    this.wizard.variabelId = opt.value;
                    this.wizard.step = 'klasifikasi';
                    this.conversation.push({ sender: 'user', type: 'text', text: opt.label });
                    await this.ensureKlasifikasis(this.wizard.moduleType, this.wizard.variabelId);
                    // Data Dictionary bubble (variabel info)
                    try {
                        const variabelList = this.wizardData.variabelsByTopik[String(this.wizard.topikId)] || [];
                        const varObj = variabelList.find(v => String(v.id) === String(this.wizard.variabelId));
                        if (varObj) {
                            const nama = varObj.nama || 'Variabel';
                            const satuan = varObj.satuan ? ` (${varObj.satuan})` : '';
                            const desc = varObj.deskripsi || varObj.keterangan || 'Deskripsi tidak tersedia.';
                            const html = `<div><div><strong>Variabel:</strong> ${nama}${satuan}</div><div class=\"mt-1 text-neutral-700\">${desc}</div></div>`;
                            this.conversation.push({ sender:'bot', type:'text', text: this.sanitizeHtml(html) });
                        }
                    } catch(_) { /* noop */ }
                    this.loadWizardKlasifikasis();
                } else if (step === 'waktu_tahun') {
                    // single-year quick select
                    const id = opt.value;
                    if (!this.wizard.tahunIds.includes(id)) this.wizard.tahunIds.push(id);
                    this.conversation.push({ sender: 'user', type: 'text', text: String(opt.label || id) });
                    if (this.wizard.moduleType !== 'lahan') {
                        this.wizard.step = 'waktu_bulan';
                        this.loadWizardBulans();
                    } else {
                        this.wizard.step = 'wilayah';
                        this.askWilayah();
                    }
                } else if (step === 'waktu_bulan_choice') {
                    // New step: choose all months or manual selection
                    this.conversation.push({ sender: 'user', type: 'text', text: opt.label });
                    if (opt.value === 'bulan_all') {
                        const bulans = this.wizardData.bulans || [];
                        this.wizard.bulanIds = bulans.map(b => b.id);
                        this.wizard.step = 'wilayah';
                        this.askWilayah();
                    } else {
                        // Render checklist for manual month selection
                        this.renderBulanChecklist();
                        this.wizard.step = 'waktu_bulan';
                    }
                } else if (step === 'wilayah_level') {
                    this.conversation.push({ sender: 'user', type: 'text', text: opt.label });
                    if (opt.value === 'nasional') {
                        this.wizard.step = 'wilayah_provinsi';
                        await this.ensureWilayahs();
                        this.askProvinces();
                    } else {
                        this.wizard.step = 'wilayah_pilih_provinsi';
                        await this.ensureWilayahs();
                        this.askProvinces(true);
                    }
                } else if (step === 'wilayah_pilih_provinsi') {
                    this.wizard.provinsiIds = [opt.value];
                    this.conversation.push({ sender: 'user', type: 'text', text: opt.label });
                    this.wizard.step = 'wilayah_kabupaten';
                    await this.ensureWilayahs();
                    this.askKabupaten(opt.value);
                } else if (step === 'choose_preview_style') {
                    this.conversation.push({ sender:'user', type:'text', text: opt.label });
                    this.presentPreview(opt.value === 'summary' ? 'summary' : 'table');
                } else if (step === 'post_preview_help') {
                    this.conversation.push({ sender:'user', type:'text', text: opt.label });
                    if (opt.value === 'yes') {
                        // Start guided flow inline without resetting chat history
                        this.startGuidedInline();
                    } else {
                        // Switch to free-typing mode: do nothing, leave conversation open
                        this.conversation.push({ sender:'bot', type:'text', text: 'Baik, silakan ketik pertanyaan Anda di bawah.' });
                        this.wizard.step = 'free_text';
                    }
                }
                this.$nextTick(()=>this.scrollChatToBottom());
            },
            renderBulanChecklist() {
                const bulans = (this.wizardData.bulans || []).map(b => ({ value: b.id, label: b.nama }));
                this.conversation.push({ sender:'bot', type:'checklist', title:'Pilih Bulan', options: bulans, selected: [] });
            },
            // Helpers for fetch
            getCsrfToken() {
                try { return (document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')) || ''; } catch (_) { return ''; }
            },
            // Basic HTML sanitizer (allow simple inline formatting; strip scripts and event handlers)
            sanitizeHtml(input) {
                try {
                    const allowedTags = new Set(['b','i','em','strong','u','br','ul','ol','li','p','span','div']);
                    const allowedAttrs = new Set(['class']);
                    const tpl = document.createElement('template');
                    tpl.innerHTML = String(input || '');
                    const walker = document.createTreeWalker(tpl.content, NodeFilter.SHOW_ELEMENT, null);
                    const toRemove = [];
                    while (walker.nextNode()) {
                        const el = walker.currentNode;
                        const tag = (el.tagName || '').toLowerCase();
                        if (!allowedTags.has(tag)) { toRemove.push(el); continue; }
                        // Strip event handlers and javascript: URLs
                        for (const attr of Array.from(el.attributes || [])) {
                            const name = attr.name.toLowerCase();
                            const val = String(attr.value || '');
                            if (!allowedAttrs.has(name) || /^on/i.test(name) || /^javascript:/i.test(val)) {
                                el.removeAttribute(attr.name);
                            }
                        }
                    }
                    for (const n of toRemove) { n.replaceWith(document.createTextNode(n.textContent || '')); }
                    return tpl.innerHTML;
                } catch (_) { return String(input || ''); }
            },
            async parseJsonOrText(res) {
                const ct = res.headers.get('content-type') || '';
                if (ct.includes('application/json')) {
                    return await res.json();
                }
                const txt = await res.text();
                // Surface first chars of HTML/text to user for easier debugging
                throw new Error(txt?.slice(0, 200) || 'Non-JSON response');
            },
            async applyStructuredSuggestion(moduleChoice, options = {}) {
                try {
                    const forceCompact = !!(options && options.forceCompact);
                    const s = this.structuredSuggestion || {};
                    const mods = Array.isArray(s.modules) ? s.modules : [];
                    // Prefer module explicitly mentioned in the user's text over the first suggestion
                    const detectExplicitModule = (txt) => {
                        const t = String(txt || '').toLowerCase();
                        if (/(benih|pupuk)/i.test(t)) return 'benih-pupuk';
                        if (/(iklim|\bopt\b|\bdpi\b|hujan|curah)/i.test(t)) return 'iklim-opt-dpi';
                        if (/(lahan|sawah|panen|kebun)/i.test(t)) return 'lahan';
                        return null;
                    };
                    const detectExplicitTopik = (txt) => {
                        const t = String(txt || '').toLowerCase();
                        if (/\bpupuk\b|\b(urea|npk|za|kcl)\b/i.test(t)) return 'pupuk';
                        if (/\bbenih\b|sebar/i.test(t)) return 'benih';
                        return null;
                    };
                    let chosenModule = moduleChoice || null;
                    if (!chosenModule) {
                        const explicit = detectExplicitModule(s.query);
                        if (explicit && mods.includes(explicit)) {
                            chosenModule = explicit;
                        }
                    }
                    if (!chosenModule) {
                        chosenModule = (mods.length ? mods[0] : (this.wizard.moduleType || this.moduleType));
                    }
                    if (!chosenModule) {
                        this.conversation.push({ sender:'bot', type:'text', text:'Tidak ada modul yang terdeteksi dari pencarian terstruktur.' });
                        return;
                    }
                    // Set module and load metadata
                    this.wizard.moduleType = chosenModule;
                    await this.ensureModuleData(chosenModule);
                    // Years: prefer explicit year (e.g., 2024)
                    const years = Array.isArray(s.years) ? s.years.map(v=>parseInt(v,10)).filter(Number.isFinite) : [];
                    if (years.length) { this.wizard.tahunIds = [Math.max(...years)]; }

                    // Wilayah: prefer exact phrase match in query; avoid selecting many provinces by default
                    const qLower = String(s.query || '').toLowerCase();
                    const wilayahHits = Array.isArray(s.wilayah_hits) ? s.wilayah_hits : [];
                    let chosenWilayah = null;
                    for (const w of wilayahHits) {
                        const nm = String(w.nama || '').toLowerCase();
                        if (nm && qLower.includes(nm)) { chosenWilayah = w; break; }
                    }
                    if (!chosenWilayah && wilayahHits.length) { chosenWilayah = wilayahHits[0]; }
                    if (chosenWilayah) {
                        // Set a single province; do not prefill multiple provinces
                        this.wizard.provinsiIds = [chosenWilayah.id].filter(Boolean);
                    } else {
                        this.wizard.provinsiIds = [];
                    }
                    this.wizard.kabupatenIds = [];

                    // Topik selection per module using backend topik hints
                    await this.ensureModuleData(chosenModule);
                    const topiks = this.wizardData.topiks || [];
                    let topikId = null;
                    const topikHintsByModule = (s.topik_hint_by_module && typeof s.topik_hint_by_module === 'object') ? s.topik_hint_by_module : {};
                    let topikHint = null;
                    if (chosenModule === 'benih-pupuk') {
                        // Prefer backend hint; fallback to explicit token detection in query
                        topikHint = (typeof s.topik_hint === 'string' && s.topik_hint) ? s.topik_hint : detectExplicitTopik(s.query);
                    } else if (chosenModule === 'iklim-opt-dpi') {
                        // Use backend module-specific hint if present
                        topikHint = topikHintsByModule['iklim-opt-dpi'] || null;
                        // Light fallback from query tokens if absent
                        if (!topikHint) {
                            const t = String(s.query||'').toLowerCase();
                            if (/(curah|hujan|suhu|kelembaban|penyinaran)/i.test(t)) topikHint = 'iklim';
                            else if (/(banjir|kekeringan|puso|terkena|terdampak)/i.test(t)) topikHint = 'dpi';
                            else if (/(\bopt\b|organisme pengganggu|hama|penyakit)/i.test(t)) topikHint = 'opt';
                        }
                    } else if (chosenModule === 'lahan') {
                        // Single topik in schema (e.g., 'Luas Lahan') – pick it if available
                        topikHint = topikHintsByModule['lahan'] || 'lahan';
                    }
                    if (topikHint) {
                        const match = topiks.find(tp => String(tp.nama||'').toLowerCase().includes(String(topikHint)));
                        if (match) topikId = match.id;
                    }
                    if (!topikId && topiks.length === 1) {
                        // If only one topik exists, select it by default
                        topikId = topiks[0].id;
                    }
                    if (topikId) {
                        this.wizard.topikId = topikId;
                        await this.ensureVariabels(chosenModule, topikId);
                    }

                    // Pick best-matching variabel by query token overlap; prioritize tokens present in the query (e.g., 'jagung')
                    let varsForModule = (s.variabel_hits && s.variabel_hits[chosenModule]) ? s.variabel_hits[chosenModule] : [];
                    // If a topik is selected and we have the module's full list for that topik, prefer that list
                    if (this.wizard.topikId) {
                        const listByTopik = this.wizardData.variabelsByTopik[String(this.wizard.topikId)] || [];
                        if (Array.isArray(listByTopik) && listByTopik.length) {
                            varsForModule = listByTopik.map(v => ({ id: v.id, nama: v.nama, topik_id: this.wizard.topikId }));
                        }
                    }
                    let pickedVar = null;
                    if (Array.isArray(varsForModule) && varsForModule.length) {
                        const qTokens = String(s.query || '').toLowerCase().split(/[^\p{L}\p{N}]+/u).filter(t=>t && t.length>=3);
                        let bestScore = -1;
                        for (const v of varsForModule) {
                            const name = String(v.nama || '').toLowerCase();
                            let score = 0; for (const t of qTokens) { if (name.includes(t)) score++; }
                            if (score > bestScore) { bestScore = score; pickedVar = v; }
                        }
                        if (!pickedVar) pickedVar = varsForModule[0];
                    }
                    if (pickedVar && pickedVar.id) {
                        this.wizard.variabelId = pickedVar.id;
                        // If variabel contains topik_id info and no topik chosen yet, align topik
                        if (!this.wizard.topikId && pickedVar.topik_id) {
                            this.wizard.topikId = pickedVar.topik_id;
                            await this.ensureVariabels(chosenModule, this.wizard.topikId);
                        }
                        await this.ensureKlasifikasis(chosenModule, pickedVar.id);
                        let klasList = this.wizardData.klasifikasisByVariabel[String(pickedVar.id)] || [];
                        // If user mentions specific klasifikasi in the query, filter to those
                        const ql = String(s.query || '').toLowerCase();
                        const wanted = [];
                        if (/hibrida/i.test(ql)) wanted.push('hibrida');
                        if (/komposit/i.test(ql)) wanted.push('komposit');
                        if (wanted.length) {
                            const subset = klasList.filter(k => wanted.some(w => String(k.nama||'').toLowerCase().includes(w)));
                            if (subset.length) klasList = subset;
                        }
                        // If user didn't specify and multiple klasifikasi exist, ask via typed clarification (no bubbles)
                        if (!wanted.length && Array.isArray(klasList) && klasList.length > 1) {
                            // Ensure months and other slots are prepared so we can finish right after reply
                            if (chosenModule !== 'lahan') {
                                const monthIds = Array.isArray(s.months) ? s.months.map(m=>parseInt(m.id,10)).filter(n=>n>=1 && n<=12) : [];
                                if (monthIds.length) {
                                    this.wizard.bulanIds = monthIds.slice(0,12);
                                } else {
                                    const preferDefault = this.compactChat || this.pendingStructured || forceCompact;
                                    if (preferDefault) {
                                        const bulans = this.wizardData.bulans || [];
                                        this.wizard.bulanIds = bulans.map(b => b.id);
                                    } else {
                                        this.wizard.step = 'waktu_bulan';
                                        this.loadWizardBulans();
                                        this.structuredSuggestion = null;
                                        return;
                                    }
                                }
                            }
                            // Ask user to type the klasifikasi name(s)
                            this.pendingPrompt = { type:'klasifikasi', candidates: (klasList||[]).map(k=>({id:k.id, nama:k.nama})), module: chosenModule, variableId: pickedVar.id };
                            const names = (klasList||[]).map(k=>k.nama).join(', ');
                            this.conversation.push({ sender:'bot', type:'text', text:`Klasifikasi apa yang Anda inginkan? Ketik salah satu atau lebih: ${names}.` });
                            this.structuredSuggestion = null;
                            return;
                        }
                        this.wizard.klasifikasiIds = klasList.map(k => k.id);
                        // Compact clarification (no bubbles): summarize inferred variable/klasifikasi
                        const klasNames = (klasList||[]).map(k=>k.nama).filter(Boolean);
                        const varName = String(pickedVar.nama||'');
                        if (this.compactChat) {
                            const klasText = klasNames.length ? `; klasifikasi: ${klasNames.join(', ')}` : '';
                            this.conversation.push({ sender:'bot', type:'text', text:`Saya gunakan variabel: ${varName}${klasText}. Ketik nama variabel/klasifikasi bila ingin diubah.` });
                        }
                    } else {
                        // If we couldn't infer a variable
                        if (this.compactChat || this.pendingStructured || forceCompact) {
                            // Stay compact: avoid option bubbles; ask user in-text
                            this.conversation.push({ sender:'bot', type:'text', text:'Saya belum menemukan variabel yang dimaksud dari kalimat tersebut. Sebutkan nama variabelnya (mis. "Benih Jagung"), nanti saya tampilkan.' });
                            // Do not switch into wizard steps here to keep flow natural/compact
                            this.structuredSuggestion = null;
                            return;
                        } else {
                            // Non-compact: fall back to guided variable pick
                            this.conversation.push({ sender:'bot', type:'text', text:'Tidak menemukan variabel yang cocok dari pencarian. Silakan pilih variabel.' });
                            await this.ensureModuleData(chosenModule);
                            const topiks = this.wizardData.topiks || [];
                            if (topiks.length) {
                                this.wizard.topikId = topiks[0].id;
                                await this.ensureVariabels(chosenModule, this.wizard.topikId);
                                this.loadWizardVariabels();
                                this.wizard.step = 'variabel';
                                this.structuredSuggestion = null;
                                return;
                            }
                        }
                    }

                    // Months: for non-lahan, use suggestions; if none, ask user to pick
                    if (chosenModule !== 'lahan') {
                        const monthIds = Array.isArray(s.months) ? s.months.map(m=>parseInt(m.id,10)).filter(n=>n>=1 && n<=12) : [];
                        if (monthIds.length) {
                            this.wizard.bulanIds = monthIds.slice(0,12);
                        } else {
                            // Compact default: pick all months to avoid extra bubbles
                            const preferDefault = this.compactChat || this.pendingStructured || forceCompact;
                            if (preferDefault) {
                                const bulans = this.wizardData.bulans || [];
                                this.wizard.bulanIds = bulans.map(b => b.id);
                            } else {
                                // Ask months explicitly (non-compact)
                                this.wizard.step = 'waktu_bulan';
                                this.loadWizardBulans();
                                this.structuredSuggestion = null;
                                return;
                            }
                        }
                    }

                    // If we have enough info, proceed to preview
                    await this.finishPreview();
                    this.structuredSuggestion = null;
                } catch (e) {
                    this.conversation.push({ sender:'bot', type:'text', text:'Gagal menerapkan hasil terstruktur.' });
                }
            },
            async runQuickStart(templateId) {
                const templates = this.getQuickStartTemplates();
                const t = templates.find(x => x.id === templateId);
                if (!t) { this.conversation.push({ sender:'bot', type:'text', text:'Template Mulai Cepat tidak tersedia.'}); return; }
                try {
                    this.isLoading = true;
                    this.wizard.moduleType = t.module;
                    await this.ensureModuleData(t.module);
                    // Pick topik by includes
                    const topiks = this.wizardData.topiks || [];
                    const tMatch = (name, s) => String(name||'').toLowerCase().includes(String(s||'').toLowerCase());
                    let topik = topiks.find(tp => tMatch(tp.nama, t.topikMatch)) || topiks[0];
                    if (!topik) { this.conversation.push({ sender:'bot', type:'text', text:'Tidak ada topik pada modul ini.'}); return; }
                    this.wizard.topikId = topik.id;
                    await this.ensureVariabels(t.module, topik.id);
                    // Pick variabel
                    const vars = this.wizardData.variabelsByTopik[String(topik.id)] || [];
                    let varObj = null;
                    if (Array.isArray(t.variabelMatches)) {
                        varObj = vars.find(v => t.variabelMatches.some(key => tMatch(v.nama, key)));
                    }
                    if (!varObj) varObj = vars[0];
                    if (!varObj) { this.conversation.push({ sender:'bot', type:'text', text:'Tidak ada variabel pada topik terpilih.'}); return; }
                    this.wizard.variabelId = varObj.id;
                    await this.ensureKlasifikasis(t.module, varObj.id);
                    // Klasifikasi: pick a few
                    const klasList = this.wizardData.klasifikasisByVariabel[String(varObj.id)] || [];
                    this.wizard.klasifikasiIds = (t.pick?.klasifikasi === 'few') ? (klasList.slice(0,3).map(k => k.id)) : [];
                    // Years
                    const years = this.wizardData.years || [];
                    const latest = years.length ? Math.max(...years) : null;
                    this.wizard.tahunIds = latest ? [latest] : (years[0] ? [years[0]] : []);
                    // Months (not for lahan)
                    if (t.module !== 'lahan') {
                        const bulans = this.wizardData.bulans || [];
                        this.wizard.bulanIds = (t.pick?.months === 'all') ? bulans.map(b => b.id) : (bulans.slice(-3).map(b => b.id));
                    } else { this.wizard.bulanIds = []; }
                    // Wilayah: pick top 5 provinces
                    await this.ensureWilayahs();
                    const provs = (this.wizardData.wilayahs || []).slice(0,5);
                    this.wizard.provinsiIds = provs.map(p => p.id);
                    this.wizard.kabupatenIds = [];
                    // Inform user
                    const label = `${(varObj.nama || 'Variabel')} — ${this.wizard.tahunIds.join(', ')}${(this.wizard.bulanIds && this.wizard.bulanIds.length)? ', semua bulan':''} (Top 5 provinsi)`;
                    this.conversation.push({ sender:'bot', type:'text', text:`Menjalankan Mulai Cepat untuk: <strong>${label}</strong>` });
                    await this.finishPreview();
                } catch (e) {
                    this.conversation.push({ sender:'bot', type:'text', text:'Gagal menjalankan Mulai Cepat: ' + (e.message || e) });
                } finally {
                    this.isLoading = false;
                }
            },
            toggleChecklist(chat, value) {
                if (!Array.isArray(chat.selected)) chat.selected = [];
                const i = chat.selected.indexOf(value);
                if (i >= 0) chat.selected.splice(i,1); else chat.selected.push(value);
            },
            clearChecklist(chat) { chat.selected = []; },
            confirmChecklist(index) {
                const chat = this.conversation[index];
                const labels = (chat.options || []).filter(o => chat.selected?.includes(o.value)).map(o => o.label);
                const step = this.wizard.step;
                this.conversation.push({ sender:'user', type:'text', text: labels.length? labels.join(', ') : '(tidak ada)' });
                if (step === 'klasifikasi') {
                    this.wizard.klasifikasiIds = chat.selected || [];
                    this.wizard.step = 'waktu_tahun';
                    this.askYears();
                } else if (step === 'waktu_tahun') {
                    // Proceed after choosing years via checklist
                    this.wizard.tahunIds = (chat.selected || []).map(v => Number(v));
                    if (this.wizard.moduleType !== 'lahan') {
                        this.wizard.step = 'waktu_bulan';
                        this.loadWizardBulans();
                    } else {
                        this.wizard.step = 'wilayah';
                        this.askWilayah();
                    }
                } else if (step === 'waktu_bulan') {
                    this.wizard.bulanIds = chat.selected || [];
                    this.wizard.step = 'wilayah';
                    this.askWilayah();
                } else if (step === 'wilayah_provinsi') {
                    this.wizard.provinsiIds = chat.selected || [];
                    this.finishPreview();
                } else if (step === 'wilayah_kabupaten') {
                    this.wizard.kabupatenIds = chat.selected || [];
                    this.finishPreview();
                }
                this.$nextTick(()=>this.scrollChatToBottom());
            },
            // Wizard data loaders and prompts
            loadWizardTopiks() {
                const topiks = this.wizardData.topiks || [];
                const opts = topiks.map(t => ({ value: t.id, label: t.nama }));
                this.conversation.push({ sender:'bot', type:'options', title:'Pilih Topik', options: opts });
            },
            loadWizardVariabels() {
                const list = (this.wizardData.variabelsByTopik[String(this.wizard.topikId)] || []);
                const opts = list.map(v => ({ value: v.id, label: v.nama + (v.satuan? ` (${v.satuan})`: '') }));
                this.conversation.push({ sender:'bot', type:'options', title:'Pilih Variabel', options: opts });
            },
            loadWizardKlasifikasis() {
                const list = (this.wizardData.klasifikasisByVariabel[String(this.wizard.variabelId)] || []);
                const opts = list.map(k => ({ value: k.id, label: k.nama }));
                this.conversation.push({ sender:'bot', type:'checklist', title:'Pilih Klasifikasi', options: opts, selected: [] });
            },
            askYears() {
                const years = this.wizardData.years || [];
                if (!years.length) { this.conversation.push({sender:'bot', type:'text', text:'Tahun tidak tersedia.'}); return; }
                // present as checklist for multi-year, with also quick options for common ranges
                const opts = years.map(y => ({ value: y, label: String(y) }));
                this.conversation.push({ sender:'bot', type:'checklist', title:'Pilih Tahun', options: opts, selected: [] });
                this.wizard.step = 'waktu_tahun';
                // Also add quick-pick for single most recent year
                const latest = Math.max(...years);
                this.conversation.push({ sender:'bot', type:'options', title:'Atau pilih cepat satu tahun', options:[{value: latest, label: `Hanya ${latest}`} ]});
            },
            loadWizardBulans() {
                // Offer a quick choice before rendering month checklist
                this.conversation.push({ sender:'bot', type:'options', title:'Gunakan semua bulan?', options:[
                    { value:'bulan_all', label:'Ya, gunakan semua bulan' },
                    { value:'bulan_manual', label:'Tidak, saya pilih bulan tertentu' },
                ]});
                this.wizard.step = 'waktu_bulan_choice';
            },
            askWilayah() {
                // choose level first
                this.conversation.push({ sender:'bot', type:'options', title:'Pilih Tingkat Wilayah', options:[
                    { value: 'nasional', label: 'Tingkat Nasional (Pilih Provinsi)' },
                    { value: 'provinsi', label: 'Tingkat Provinsi (Pilih Kabupaten/Kota)' },
                ]});
                this.wizard.step = 'wilayah_level';
            },
            askProvinces(single=false) {
                const provinces = (this.wizardData.wilayahs || []).map(p => ({ value: p.id, label: p.nama }));
                if (single) {
                    this.conversation.push({ sender:'bot', type:'options', title:'Pilih Provinsi', options: provinces });
                } else {
                    this.conversation.push({ sender:'bot', type:'checklist', title:'Pilih Provinsi', options: provinces, selected: [] });
                    this.wizard.step = 'wilayah_provinsi';
                }
            },
            askKabupaten(provId) {
                const prov = (this.wizardData.wilayahs || []).find(p => String(p.id) === String(provId));
                const opts = (prov?.kabupaten || []).map(k => ({ value: k.id, label: k.nama }));
                this.conversation.push({ sender:'bot', type:'checklist', title:`Pilih Kabupaten/Kota di ${prov?.nama || 'Provinsi'}`, options: opts, selected: [] });
                this.wizard.step = 'wilayah_kabupaten';
            },
            async ensureModuleData(module){
                // Only skip if all critical datasets exist; otherwise fetch them
                const hasTopiks = Array.isArray(this.wizardData.topiks) && this.wizardData.topiks.length > 0;
                const hasYears = Array.isArray(this.wizardData.years) && this.wizardData.years.length > 0;
                const needsBulans = module !== 'lahan';
                const hasBulans = Array.isArray(this.wizardData.bulans) && this.wizardData.bulans.length > 0;
                if ((module === this.moduleType) && hasTopiks && hasYears && (!needsBulans || hasBulans)) {
                    return; // everything present
                }
                try {
                    this.isLoading = true;
                    const base = `/api/${module}`;
                    const [topiksRes, yearsRes, bulansRes] = await Promise.all([
                        fetch(`${base}/topiks`),
                        fetch(`${base}/years`),
                        module !== 'lahan' ? fetch(`${base}/bulans`) : Promise.resolve({ ok: true, json: async ()=>([]) })
                    ]);
                    const topiks = await topiksRes.json();
                    const years = await yearsRes.json();
                    const bulans = module !== 'lahan' ? await bulansRes.json() : [];
                    this.wizardData.topiks = topiks || [];
                    this.wizardData.variabelsByTopik = {};
                    this.wizardData.klasifikasisByVariabel = {};
                    this.wizardData.years = Array.isArray(years) ? years : (years?.data || []);
                    this.wizardData.bulans = Array.isArray(bulans) ? bulans : (bulans?.data || []);
                } finally { this.isLoading = false; }
            },
            async ensureVariabels(module, topikId){
                const key = String(topikId);
                if (this.wizardData.variabelsByTopik[key]) return;
                const res = await fetch(`/api/${module}/variabels/${topikId}`);
                const data = await res.json();
                this.wizardData.variabelsByTopik[key] = data || [];
            },
            async ensureKlasifikasis(module, variabelId){
                const key = String(variabelId);
                if (this.wizardData.klasifikasisByVariabel[key]) return;
                const res = await fetch(`/api/${module}/klasifikasis`, {
                    method:'POST',
                    headers:{
                        'Content-Type':'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': this.getCsrfToken(),
                    },
                    body: JSON.stringify({ variabel_ids: [variabelId] })
                });
                const list = await this.parseJsonOrText(res);
                this.wizardData.klasifikasisByVariabel[key] = list || [];
            },
            async ensureWilayahs(){
                if (this.wizardData.wilayahs && this.wizardData.wilayahs.length) return;
                const res = await fetch('/pertanian/wilayahs');
                const list = await res.json();
                this.wizardData.wilayahs = list || [];
            },
            async finishPreview() {
                // Build minimal payload for preview
                const isLahan = this.wizard.moduleType === 'lahan';
                const selections = [{
                    variabel_id: this.wizard.variabelId,
                    klasifikasi_ids: this.wizard.klasifikasiIds,
                    ...(isLahan ? { tahuns: this.wizard.tahunIds } : { tahun_ids: this.wizard.tahunIds, bulan_ids: this.wizard.bulanIds })
                }];
                const config = {
                    tata_letak: 'tipe_1',
                    provinsi_ids: this.wizard.provinsiIds,
                    kabupaten_ids: this.wizard.kabupatenIds,
                };
                // Client-side sanity checks to avoid server validation redirects/HTML
                if (!this.wizard.variabelId) { this.conversation.push({ sender:'bot', type:'text', text:'Variabel belum dipilih.' }); return; }
                if (!Array.isArray(this.wizard.klasifikasiIds) || this.wizard.klasifikasiIds.length === 0) { this.conversation.push({ sender:'bot', type:'text', text:'Pilih minimal satu klasifikasi.' }); return; }
                if (!Array.isArray(this.wizard.tahunIds) || this.wizard.tahunIds.length === 0) { this.conversation.push({ sender:'bot', type:'text', text:'Pilih minimal satu tahun.' }); return; }
                if (!isLahan && (!Array.isArray(this.wizard.bulanIds) || this.wizard.bulanIds.length === 0)) { this.conversation.push({ sender:'bot', type:'text', text:'Pilih minimal satu bulan.' }); return; }
                const wilayahCount = (config.provinsi_ids?.length || 0) + (config.kabupaten_ids?.length || 0);
                if (wilayahCount === 0) { this.conversation.push({ sender:'bot', type:'text', text:'Pilih minimal satu wilayah.' }); return; }
                try {
                    this.isLoading = true;
                    const res = await fetch(`/pertanian/${this.wizard.moduleType}/filter`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': this.getCsrfToken(),
                        },
                        body: JSON.stringify({ selections, config })
                    });
                    const data = await this.parseJsonOrText(res);
                    if (!res.ok) throw new Error(data?.message || 'Gagal memuat pratinjau');
                    // Build minimal meta text for preview
                    let topikObj = (this.wizardData.topiks || []).find(t => String(t.id) === String(this.wizard.topikId));
                    if (!topikObj && this.wizard.moduleType === 'benih-pupuk') {
                        // Derive topik by variable or hint when missing
                        const explicitTopik = /\b(pupuk)\b/i.test(String(this.structuredSuggestion?.query||'')) ? 'pupuk' : (/\b(benih)\b|sebar/i.test(String(this.structuredSuggestion?.query||'')) ? 'benih' : null);
                        if (explicitTopik) {
                            const tps = this.wizardData.topiks || [];
                            topikObj = tps.find(tp => String(tp.nama||'').toLowerCase().includes(explicitTopik));
                        }
                    }
                    const variabelList = this.wizardData.variabelsByTopik[String(this.wizard.topikId)] || [];
                    const varObj = variabelList.find(v => String(v.id) === String(this.wizard.variabelId));
                    const klasList = (this.wizardData.klasifikasisByVariabel[String(this.wizard.variabelId)] || [])
                        .filter(k => (this.wizard.klasifikasiIds || []).map(String).includes(String(k.id)))
                        .map(k => k.nama);
                    const tablePayload = { headers: data.headers || [], rows: data.rows || [] };
                    const meta = {
                            module: this.wizard.moduleType?.replace('benih-pupuk','Benih & Pupuk')?.replace('iklim-opt-dpi','Iklim & OPT DPI')?.replace('lahan','Lahan'),
                            topik: topikObj?.nama || null,
                            variabel: varObj ? (varObj.nama + (varObj.satuan ? ` (${varObj.satuan})` : '')) : null,
                            klasifikasi: klasList.length ? klasList.join(', ') : null,
                    };
                    // Always show summary only (compact assistant behavior)
                    this.wizard._pendingPreview = { results: tablePayload, meta, selections, config, moduleType: this.wizard.moduleType };
                    const lines = this.buildSummaryLines(tablePayload);
                    this.conversation.push({ sender:'bot', type:'summary', title:'Ringkasan', summaryLines: lines, meta, payload: this.wizard._pendingPreview });
                    this.wizard.step = 'preview';
                    // Ask (typed) whether user wants a tutorial to find full table on module page
                    const modSlug = this.wizard.moduleType;
                    const labelMod = meta.module || modSlug;
                    this.pendingPrompt = { type:'tutorial', module: modSlug, preview: this.wizard._pendingPreview };
                    this.renderBotText(`Perlu panduan mencari tabel lengkap di halaman ${labelMod}? Ketik "ya" untuk pandu, atau ketik bebas untuk lanjut.`);
                } catch (e) {
                    this.conversation.push({ sender:'bot', type:'text', text: 'Gagal memuat pratinjau: ' + (e.message || e) });
                } finally {
                    this.isLoading = false;
                    this.$nextTick(()=>this.scrollChatToBottom());
                }
            },
            // After choosing a preview style, render it
            presentPreview(style) {
                const pend = this.wizard._pendingPreview; if (!pend) return;
                // Force summary always
                const lines = this.buildSummaryLines(pend.results);
                this.conversation.push({ sender:'bot', type:'summary', title:'Ringkasan', summaryLines: lines, meta: pend.meta, payload: pend });
                this.wizard.step = 'preview';
                // After summary, ask for tutorial via typed confirmation
                this.pendingPrompt = { type:'tutorial', module: pend.moduleType || (this.wizard.moduleType), preview: pend };
                const labelMod = (pend.meta?.module) || (this.wizard.moduleType?.replace('benih-pupuk','Benih & Pupuk')?.replace('iklim-opt-dpi','Iklim & OPT DPI')?.replace('lahan','Lahan'));
                this.renderBotText(`Perlu panduan mencari tabel lengkap di halaman ${labelMod}? Ketik "ya" untuk pandu, atau ketik bebas untuk lanjut.`);
            },
            showAsTable(chat) {
                if (chat && chat.type === 'summary') {
                    this.conversation.push({ sender:'bot', type:'table', title:'Pratinjau Hasil', results: chat.payload.results, meta: chat.meta, payload: chat.payload });
                }
            },
            buildSummaryLines(tableData) {
                const lines = [];
                const headers = (tableData?.headers || []);
                const last = headers.length ? headers[headers.length-1] : [];
                const colNames = last.map(h => h.name).filter(n => n && n !== 'Wilayah');
                const rows = (tableData?.rows || []).slice(0, 3); // limit a few wilayah
                rows.forEach(r => {
                    // take first non-null value for each column in this wilayah
                    const pairs = [];
                    r.values.forEach((v, i) => {
                        const name = colNames[i];
                        if (name && v !== null && v !== undefined) {
                            pairs.push(`${name}: ${typeof v === 'number' ? v.toLocaleString('id-ID', {minimumFractionDigits:2, maximumFractionDigits:2}) : v}`);
                        }
                    });
                    if (pairs.length) {
                        lines.push(`${r.wilayah} — ${pairs.slice(0, 6).join(', ')}`);
                    }
                });
                if (!lines.length) lines.push('Tidak ada data untuk diringkas.');
                return lines;
            },
            buildTutorialText(moduleSlug, pend) {
                try {
                    const labelMod = (pend?.meta?.module) || (String(moduleSlug||'').replace('benih-pupuk','Benih & Pupuk').replace('iklim-opt-dpi','Iklim & OPT DPI').replace('lahan','Lahan'));
                    const url = `/pertanian/${moduleSlug}`;
                    const meta = pend?.meta || {};
                    const sel = pend?.selections?.[0] || {};
                    const cfg = pend?.config || {};
                    const wilayahNames = (pend?.results?.rows||[]).slice(0,3).map(r=>r.wilayah).filter(Boolean);
                    const wilayahText = wilayahNames.length ? wilayahNames.join(', ') + ( (pend?.results?.rows||[]).length>3 ? ', dan lainnya' : '' ) : 'sesuai pratinjau';
                    const tahun = (sel.tahun_ids && sel.tahun_ids.length) ? sel.tahun_ids.join(', ') : (sel.tahuns && sel.tahuns.length ? sel.tahuns.join(', ') : 'terbaru');
                    const bulanIds = Array.isArray(sel.bulan_ids) ? sel.bulan_ids : [];
                    const bulanText = bulanIds.length ? 'semua bulan yang tersedia' : (moduleSlug==='lahan' ? '(tidak bulanan)' : 'semua bulan');
                    const steps = [
                        `Buka halaman ${labelMod}: <a href="${url}" target="_blank" rel="noopener">${url}</a>`,
                        `Pilih modul: ${labelMod}`,
                        meta.topik ? `Pilih topik: ${meta.topik}` : null,
                        meta.variabel ? `Pilih variabel: ${meta.variabel}` : null,
                        meta.klasifikasi ? `Centang klasifikasi: ${meta.klasifikasi}` : 'Centang klasifikasi yang diinginkan',
                        `Pilih tahun: ${tahun}`,
                        moduleSlug!=='lahan' ? `Pilih bulan: ${bulanText}` : null,
                        `Pilih wilayah: ${wilayahText}`,
                        `Atur tata letak tabel: ${cfg.tata_letak || 'tipe_1'}`,
                        `Tekan Tampilkan untuk melihat tabel lengkap.`
                    ].filter(Boolean);
                    return `Tutorial singkat membuka tabel lengkap:\n- ` + steps.join('\n- ');
                } catch(_) { return 'Tutorial tidak tersedia saat ini.'; }
            },
            saveWizardResult(chat) {
                const payload = chat?.payload; if (!payload) return;
                const results = chat?.results || chat?.payload?.results || { headers: [], rows: [] };
                const idx = this.storedResults.length + 1;
                const stored = {
                    id: Date.now(),
                    title: `Hasil ${idx}`,
                    timestamp: new Date().toLocaleString('id-ID'),
                    results,
                    config: payload.config,
                    selections: payload.selections,
                    exportSelections: payload.selections,
                    exportConfig: payload.config,
                };
                this.storedResults.push(stored);
                this.selectedResultIndex = this.storedResults.length - 1;
                this.conversation.push({ sender:'bot', type:'text', text: 'Hasil disimpan ke panel. Anda dapat membuka tab Tabel/Grafik untuk melihat lebih lengkap.' });
            },
            
                // Methods
                selectTopik(id) {
                    this.selection.topik_id = id;
                    this.selection.variabel_id = null;
                    this.selection.klasifikasi_ids = [];
                },

                selectVariabel(id) {
                    this.selection.variabel_id = id;
                    this.selection.klasifikasi_ids = [];
                },

                isSelectionValid() {
                    const hasKlasifikasiOptions = this.filteredKlasifikasi.length > 0;
                    const isKlasifikasiValid = !hasKlasifikasiOptions || (hasKlasifikasiOptions && this.selection.klasifikasi_ids.length > 0);

                    const requireBulan = this.moduleType !== 'lahan';
                    const bulanOk = requireBulan ? this.selection.bulan_ids.length > 0 : true;

                    return this.selection.topik_id &&
                        this.selection.variabel_id &&
                        isKlasifikasiValid &&
                        this.selection.tahun_ids.length > 0 &&
                        bulanOk;
                },

                addSelection() {
                    if (!this.isSelectionValid()) return;

                    const topik = this.allData.topiks.find(t => String(t.id) === String(this.selection.topik_id));
                    const variabel = this.allData.variabels.find(v => String(v.id) === String(this.selection.variabel_id));
                    
                    // Map klasifikasi IDs to names with proper ID comparison
                    const klasifikasiNames = this.selection.klasifikasi_ids.map(id => {
                        const klasifikasi = this.allData.klasifikasis.find(k => String(k.id) === String(id));
                        return klasifikasi?.nama || '';
                    }).filter(name => name !== '');
                    
                    const tahun_awal = Math.min(...this.selection.tahun_ids);
                    const tahun_akhir = Math.max(...this.selection.tahun_ids);
                    const bulan_awal = this.moduleType !== 'lahan' ? (this.allData.bulans.find(b => b.id == Math.min(...this.selection.bulan_ids))?.nama || '') : '';
                    const bulan_akhir = this.moduleType !== 'lahan' ? (this.allData.bulans.find(b => b.id == Math.max(...this.selection.bulan_ids))?.nama || '') : '';

                    const newSelection = {
                        id: Date.now(),
                        topik_nama: topik?.nama || '',
                        variabel_nama: variabel?.nama || '',
                        variabel_satuan: variabel?.satuan || '',
                        klasifikasi_nama: klasifikasiNames.join(', ') || 'Semua',
                        tahun_awal,
                        tahun_akhir,
                        bulan_awal,
                        bulan_akhir,
                        // Store the actual selection data for processing
                        topik_id: this.selection.topik_id,
                        variabel_id: this.selection.variabel_id,
                        klasifikasi_ids: [...this.selection.klasifikasi_ids],
                        tahun_ids: [...this.selection.tahun_ids],
                        bulan_ids: this.moduleType !== 'lahan' ? [...this.selection.bulan_ids] : []
                    };
                    
                    this.selections.push(newSelection);
                    this.resetSelection();
                },

                removeSelection() {
                    const idsToRemove = this.selectedForRemoval.map(Number);
                    this.selections = this.selections.filter(item => !idsToRemove.includes(item.id));
                    this.selectedForRemoval = [];
                },

                resetSelection() {
                    this.selection.topik_id = null;
                    this.selection.variabel_id = null;
                    this.selection.klasifikasi_ids = [];
                    this.selection.tahun_ids = [];
                    this.selection.bulan_ids = [];
                },

                resetForm() {
                    this.selection = {
                        topik_id: null,
                        variabel_id: null,
                        klasifikasi_ids: [],
                        tahun_ids: [],
                        bulan_ids: [],
                        provinsi_ids: [],
                        kabupaten_ids: [],
                        tata_letak: 'tipe_1',
                        wilayah: { selected_provinsi: null },
                    };
                    this.selections = [];
                    this.searchResults = { headers: [], rows: [], config: {} };
                    this.selectedForRemoval = [];
                    this.wilayahLevel = 'nasional';
                    this.selectedProvinsiId = null;
                },

                selectStoredResult(index) {
                    this.selectedResultIndex = index;
                    if (this.activeResultTab === 'grafik') {
                        this.$nextTick(() => this.renderChart());
                    }
                },

                toggleResultSelection(id, checked) {
                    const nid = Number(id);
                    if (checked) {
                        if (!this.selectedResultIds.includes(nid)) this.selectedResultIds.push(nid);
                    } else {
                        this.selectedResultIds = this.selectedResultIds.filter(x => x !== nid);
                    }
                },

                removeSelectedResults() {
                    if (!this.selectedResultIds || this.selectedResultIds.length === 0) return;
                    const ids = new Set(this.selectedResultIds.map(Number));
                    const prevSelected = (this.selectedResultIndex !== null && this.storedResults[this.selectedResultIndex]) ? this.storedResults[this.selectedResultIndex].id : null;
                    this.storedResults = this.storedResults.filter(r => !ids.has(Number(r.id)));
                    this.selectedResultIds = [];

                    if (this.storedResults.length === 0) {
                        this.selectedResultIndex = null;
                        if (window.myChart && typeof window.myChart.destroy === 'function') window.myChart.destroy();
                        return;
                    }

                    let newIndex = null;
                    if (prevSelected != null) newIndex = this.storedResults.findIndex(r => Number(r.id) === Number(prevSelected));
                    if (newIndex === -1 || newIndex === null) newIndex = Math.min(this.selectedResultIndex ?? 0, this.storedResults.length - 1);
                    this.selectStoredResult(newIndex);
                },

                clearAllResults() {
                    if (!this.storedResults || this.storedResults.length === 0) return;
                    this.showClearConfirm = true;
                },

                clearAllResultsConfirmed() {
                    this.storedResults = [];
                    this.selectedResultIds = [];
                    this.selectedResultIndex = null;
                    this.showClearConfirm = false;
                    if (window.myChart && typeof window.myChart.destroy === 'function') window.myChart.destroy();
                },

                async fetchData() {
                    if (this.selections.length === 0) {
                        alert('Silakan tambahkan data terlebih dahulu.');
                        return;
                    }
                    this.isProcessing = true;

                    try {
                        const isLahan = this.moduleType === 'lahan';
                        const payload = {
                            selections: this.selections.map(s => {
                                const base = {
                                    variabel_id: s.variabel_id,
                                    klasifikasi_ids: s.klasifikasi_ids,
                                };
                                if (isLahan) {
                                    return { ...base, tahuns: s.tahun_ids };
                                } else {
                                    return { ...base, tahun_ids: s.tahun_ids, bulan_ids: s.bulan_ids || [] };
                                }
                            }),
                            config: {
                                tata_letak: this.selection.tata_letak,
                                provinsi_ids: this.wilayahLevel === 'nasional' ? this.selection.provinsi_ids : [],
                                kabupaten_ids: this.wilayahLevel === 'provinsi' ? this.selection.kabupaten_ids : [],
                            }
                        };

                        const response = await fetch(`/pertanian/${this.moduleType}/filter`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify(payload)
                        });

                        if (!response.ok) {
                            const errorData = await response.json();
                            throw new Error(errorData.message || 'Network response was not ok');
                        }

                        const results = await response.json();
                        // Expecting results = { headers: HeaderRow[], rows: Row[], config: {...} }

                        const resultIndex = this.storedResults.length + 1;
                        const storedResult = {
                            id: Date.now(),
                            title: `Hasil ${resultIndex}`,
                            timestamp: new Date().toLocaleString('id-ID'),
                            results: results,
                            config: { ...payload.config },
                            selections: this.selections.map(s => ({ ...s })),
                            exportSelections: payload.selections,
                            exportConfig: payload.config,
                        };

                        this.storedResults.push(storedResult);
                        this.selectedResultIndex = this.storedResults.length - 1;

                    } catch (error) {
                        alert('Terjadi kesalahan saat mengambil data: ' + error.message);
                    } finally {
                        this.isProcessing = false;
                    }
                },

                exportExcel() {
                    const currentResult = this.selectedResultIndex !== null ? this.storedResults[this.selectedResultIndex] : null;
                    if (!currentResult || !currentResult.results) return;

                    // Temporarily disable beforeunload prompt during export
                    this.skipUnloadPrompt = true;

                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/pertanian/${this.moduleType}/export`;
                    form.style.display = 'none';
                    
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    form.appendChild(csrfToken);

                    // Build selections[] inputs
                    const selections = currentResult.exportSelections || [];
                    const isLahan = this.moduleType === 'lahan';
                    selections.forEach((sel, idx) => {
                        const mk = (name, value) => { const el = document.createElement('input'); el.type='hidden'; el.name = `selections[${idx}][${name}]`; el.value = value; form.appendChild(el); };
                        mk('variabel_id', sel.variabel_id);
                        const yearField = isLahan ? 'tahuns' : 'tahun_ids';
                        (sel.tahun_ids || sel.tahuns || []).forEach(v => { const el = document.createElement('input'); el.type='hidden'; el.name = `selections[${idx}][${yearField}][]`; el.value = v; form.appendChild(el); });
                        (sel.klasifikasi_ids || []).forEach(v => { const el = document.createElement('input'); el.type='hidden'; el.name = `selections[${idx}][klasifikasi_ids][]`; el.value = v; form.appendChild(el); });
                        if (!isLahan) { (sel.bulan_ids || []).forEach(v => { const el = document.createElement('input'); el.type='hidden'; el.name = `selections[${idx}][bulan_ids][]`; el.value = v; form.appendChild(el); }); }
                    });

                    // Build config inputs
                    const cfg = currentResult.exportConfig || { tata_letak: this.selection.tata_letak, provinsi_ids: [], kabupaten_ids: [] };
                    const cfgTata = document.createElement('input'); cfgTata.type='hidden'; cfgTata.name='config[tata_letak]'; cfgTata.value = cfg.tata_letak || 'tipe_1'; form.appendChild(cfgTata);
                    (cfg.provinsi_ids || []).forEach(v => { const el = document.createElement('input'); el.type='hidden'; el.name='config[provinsi_ids][]'; el.value=v; form.appendChild(el); });
                    (cfg.kabupaten_ids || []).forEach(v => { const el = document.createElement('input'); el.type='hidden'; el.name='config[kabupaten_ids][]'; el.value=v; form.appendChild(el); });

                    // Optional: filename
                    const filename = document.createElement('input'); filename.type='hidden'; filename.name='filename'; filename.value = `laporan-${this.moduleType}-${Date.now()}.xlsx`; form.appendChild(filename);
                    
                    document.body.appendChild(form);
                    form.submit();
                    document.body.removeChild(form);

                    // Re-enable prompt after export completes (best-effort)
                    const reset = () => { this.skipUnloadPrompt = false; window.removeEventListener('focus', reset); };
                    window.addEventListener('focus', reset);
                    setTimeout(reset, 3000);
                },

                // Dependent selection reset
                loadVariabels() { this.selection.variabel_id = null; this.selection.klasifikasi_ids = []; },
                loadKlasifikasis() { this.selection.klasifikasi_ids = []; },

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

                toggleKabupaten(id) {
                    const index = this.selection.kabupaten_ids.indexOf(id);
                    if (index > -1) { this.selection.kabupaten_ids.splice(index, 1); } else { this.selection.kabupaten_ids.push(id); }
                },
                selectAllKabupatenInSelectedProvinsi() {
                    if (!this.selectedProvinsiId) return;
                    const selectedProvinsi = this.allData.wilayahs.find(p => p.id == this.selectedProvinsiId);
                    if (selectedProvinsi && selectedProvinsi.kabupaten) {
                        this.selection.kabupaten_ids = selectedProvinsi.kabupaten.map(k => k.id);
                    }
                },
                clearKabupatenInSelectedProvinsi() {
                    if (!this.selectedProvinsiId) { this.selection.kabupaten_ids = []; return; }
                    const selectedProvinsi = this.allData.wilayahs.find(p => p.id == this.selectedProvinsiId);
                    if (selectedProvinsi && selectedProvinsi.kabupaten) {
                        const kabupatenIdsInSelectedProvinsi = selectedProvinsi.kabupaten.map(k => k.id);
                        this.selection.kabupaten_ids = this.selection.kabupaten_ids.filter(id => !kabupatenIdsInSelectedProvinsi.includes(id));
                    }
                },
                toggleWilayah(id) {
                    if (this.wilayahLevel === 'nasional') {
                        const index = this.selection.provinsi_ids.indexOf(id);
                        if (index > -1) { this.selection.provinsi_ids.splice(index, 1); } else { this.selection.provinsi_ids.push(id); }
                    } else {
                        this.toggleKabupaten(id);
                    }
                },

                // Aliases to server-provided structures
                get dynamicHeaders() {
                    const currentResult = this.selectedResultIndex !== null ? this.storedResults[this.selectedResultIndex] : null;
                    return currentResult?.results?.headers || [];
                },
                get dynamicRows() {
                    const currentResult = this.selectedResultIndex !== null ? this.storedResults[this.selectedResultIndex] : null;
                    const rows = currentResult?.results?.rows || [];
                    // Keep sorter behavior if provided
                    const sorted = [...rows];
                    sorted.sort((a, b) => {
                        const aHas = a.wilayah_sorter !== undefined && a.wilayah_sorter !== null;
                        const bHas = b.wilayah_sorter !== undefined && b.wilayah_sorter !== null;
                        if (aHas && bHas) {
                            if (a.wilayah_sorter !== b.wilayah_sorter) return a.wilayah_sorter - b.wilayah_sorter;
                            return String(a.wilayah).localeCompare(String(b.wilayah));
                        }
                        if (aHas && !bHas) return -1; // rows with sorter come first
                        if (!aHas && bHas) return 1;
                        // both missing sorter: sort by name
                        return String(a.wilayah).localeCompare(String(b.wilayah));
                    });
                    return sorted;
                },

                renderChart() {
                    const canvas = document.getElementById('dynamicChart');
                    if (!canvas) return;
                    const ctx = canvas.getContext('2d');
                    if (window.myChart) { window.myChart.destroy(); }
                    if (!this.dynamicRows || this.dynamicRows.length === 0) return;

                    const labels = this.dynamicRows.map(row => row.wilayah);
                    const datasets = [];
                    const colors = [
                        'rgba(54, 162, 235, 0.8)', 'rgba(255, 99, 132, 0.8)', 'rgba(75, 192, 192, 0.8)',
                        'rgba(255, 206, 86, 0.8)', 'rgba(153, 102, 255, 0.8)', 'rgba(255, 159, 64, 0.8)',
                        'rgba(201, 203, 207, 0.8)', 'rgba(54, 162, 235, 0.6)', 'rgba(255, 99, 132, 0.6)'
                    ];

                    const columnLabels = [];
                    if (this.dynamicHeaders && this.dynamicHeaders.length > 0) {
                        const lastHeaderRow = this.dynamicHeaders[this.dynamicHeaders.length - 1];
                        lastHeaderRow.forEach(header => { if (header.name !== 'Wilayah') columnLabels.push(header.name); });
                    }

                    if (this.dynamicRows.length > 0 && columnLabels.length > 0) {
                        columnLabels.forEach((columnLabel, index) => {
                            const data = this.dynamicRows.map(row => {
                                const value = row.values ? row.values[index] : null;
                                return value !== null && value !== undefined ? parseFloat(value) || 0 : 0;
                            });
                            datasets.push({
                                label: columnLabel,
                                data: data,
                                backgroundColor: colors[index % colors.length],
                                borderColor: colors[index % colors.length].replace('0.8', '1'),
                                borderWidth: 1
                            });
                        });
                    }

                    const scrollWrap = document.getElementById('chart-scroll');
                    const containerWidth = scrollWrap ? scrollWrap.clientWidth : 800;
                    const containerHeight = scrollWrap ? scrollWrap.clientHeight : 384;
                    const perLabelWidth = Math.max(70, (datasets.length || 1) * 18 + 40);
                    const desiredWidth = Math.max(containerWidth, (labels.length || 1) * perLabelWidth);
                    ctx.canvas.style.width = desiredWidth + 'px';
                    ctx.canvas.style.height = containerHeight + 'px';
                    ctx.canvas.width = desiredWidth; // important when responsive:false
                    ctx.canvas.height = containerHeight;

                    window.myChart = new Chart(ctx, {
                        type: 'bar',
                        data: { labels: labels, datasets: datasets },
                        options: {
                            responsive: false,
                            scales: { y: { beginAtZero: true } },
                            plugins: { legend: { display: this.showLegend, position: 'top' } }
                        }
                    });
                },

                toggleLegend() { this.showLegend = !this.showLegend; this.renderChart(); },
                scrollToProvince() {
                    if (!this.selectedProvinceForScroll) return;
                    const scrollWrap = document.getElementById('chart-scroll');
                    if (!scrollWrap) return;
                    const labels = this.dynamicRows.map(row => row.wilayah);
                    const index = labels.indexOf(this.selectedProvinceForScroll);
                    if (index === -1) return;
                    const perLabelWidth = Math.max(70, 120);
                    const scrollPosition = index * perLabelWidth;
                    const containerWidth = scrollWrap.clientWidth;
                    const next = Math.max(0, scrollPosition - containerWidth / 2);
                    scrollWrap.scrollTo({ left: next, behavior: 'smooth' });
                },
                initChartResizeHandlerOnce: (function() { let initialized = false; return function() { if (!initialized) { initialized = true; window.addEventListener('resize', () => { this.renderChart(); }); } }; })(),
                
                // ChatBot Methods
                renderBotText(text) {
                    this.conversation.push({ sender: 'bot', type: 'text', text: this.sanitizeHtml(String(text || '')) });
                },
                switchChatMode(mode, { silent = false } = {}) {
                    const m = String(mode || '').toLowerCase();
                    if (!['natural','structured','guided'].includes(m)) return;
                    this.chatMode = m;
                    if (!silent) {
                        const label = m === 'natural' ? 'chat bebas' : (m === 'structured' ? 'pencarian terstruktur' : 'pandu');
                        this.renderBotText(`Mode diubah ke ${label}.`);
                    }
                    // If switching to guided, ensure the wizard has a prompt on screen
                    if (m === 'guided' && (!this.conversation || this.conversation.length === 0)) {
                        this.resetGuidedChat();
                    }
                    this.$nextTick(() => this.scrollChatToBottom());
                },
                async rePromptCurrentStep() {
                    try {
                        const step = this.wizard.step;
                        if (step === 'module') {
                            this.conversation.push({ sender:'bot', type:'options', title:'Pilih Modul', options:[
                                { value:'benih-pupuk', label:'Benih & Pupuk' },
                                { value:'lahan', label:'Lahan' },
                                { value:'iklim-opt-dpi', label:'Iklim & OPT DPI' },
                            ]});
                        } else if (step === 'topik') {
                            await this.ensureModuleData(this.wizard.moduleType || this.moduleType);
                            this.loadWizardTopiks();
                        } else if (step === 'variabel') {
                            await this.ensureVariabels(this.wizard.moduleType || this.moduleType, this.wizard.topikId);
                            this.loadWizardVariabels();
                        } else if (step === 'klasifikasi') {
                            await this.ensureKlasifikasis(this.wizard.moduleType || this.moduleType, this.wizard.variabelId);
                            this.loadWizardKlasifikasis();
                        } else if (step === 'waktu_tahun') {
                            this.askYears();
                        } else if (step === 'waktu_bulan_choice') {
                            this.loadWizardBulans();
                        } else if (step === 'waktu_bulan') {
                            this.renderBulanChecklist();
                        } else if (step === 'wilayah_level') {
                            this.askWilayah();
                        } else if (step === 'wilayah_provinsi') {
                            await this.ensureWilayahs();
                            this.askProvinces();
                        } else if (step === 'wilayah_pilih_provinsi') {
                            await this.ensureWilayahs();
                            this.askProvinces(true);
                        } else if (step === 'wilayah_kabupaten') {
                            await this.ensureWilayahs();
                            const provId = (this.wizard.provinsiIds && this.wizard.provinsiIds[0]) || this.selectedProvinsiId;
                            if (provId) this.askKabupaten(provId);
                        } else if (step === 'choose_preview_style') {
                            // We no longer offer style options; always show summary
                            this.presentPreview('summary');
                        } else if (step === 'preview' || step === 'post_preview_help') {
                            // After summary we ask for tutorial via typed prompt elsewhere; nothing to re-prompt here
                        } else {
                            // Default: restart guided prompt
                            this.resetGuidedChat();
                        }
                    } finally {
                        this.$nextTick(() => this.scrollChatToBottom());
                    }
                },
                async handleNaturalMessage(messageToSend) {
                    // Intercept pending typed clarifications first
                    if (this.pendingPrompt && this.pendingPrompt.type === 'klasifikasi') {
                        const raw = String(messageToSend||'');
                        const lower = raw.toLowerCase();
                        const cand = Array.isArray(this.pendingPrompt.candidates)?this.pendingPrompt.candidates:[];
                        const matched = cand.filter(k => lower.includes(String(k.nama||'').toLowerCase()));
                        if (!matched.length) {
                            this.renderBotText('Saya belum mengenali klasifikasi dari teks tersebut. Ketik salah satu nama klasifikasi yang ada.');
                            return;
                        }
                        this.wizard.klasifikasiIds = matched.map(k=>k.id);
                        this.pendingPrompt = null;
                        // Proceed to preview now that klasifikasi chosen
                        await this.finishPreview();
                        return;
                    }
                    // Intercept tutorial confirmation
                    if (this.pendingPrompt && this.pendingPrompt.type === 'tutorial') {
                        const raw = String(messageToSend||'');
                        const l = raw.trim().toLowerCase();
                        const yes = /^(ya|iya|ok|oke|baik|lanjut|mau|boleh|yes|y)$/i.test(l);
                        const no  = /^(tidak|ga|gak|nggak|enggak|no|n|skip|nanti)$/i.test(l);
                        const pend = this.pendingPrompt.preview;
                        const mod = this.pendingPrompt.module;
                        this.pendingPrompt = null;
                        if (yes) {
                            this.renderBotText(this.buildTutorialText(mod, pend));
                            return;
                        }
                        if (no) {
                            this.renderBotText('Baik, lanjutkan chat bebas.');
                            return;
                        }
                        // neither yes nor no: continue as normal
                    }
                    // If user confirms to show results and we have a pending structured plan, execute it directly
                    const raw = String(messageToSend || '');
                    const txtLower = raw.trim().toLowerCase();
                    if (this.pendingStructured && /\b(tampilkan|ya tampilkan|ok tampilkan|silakan tampilkan|tolong tampilkan)\b/i.test(txtLower)) {
                        this.pendingStructured = false;
                        // Acknowledge and run structured suggestion
                        this.renderBotText('Baik, sedang saya ambilkan datanya...');
                        try { await this.applyStructuredSuggestion(null); } catch(_) {}
                        this.$nextTick(() => this.scrollChatToBottom());
                        return;
                    }
                    this.isLoading = true;
                    try {
                        const csrfEl = typeof document !== 'undefined' ? document.querySelector('meta[name="csrf-token"]') : null;
                        const csrf = csrfEl ? csrfEl.getAttribute('content') : null;
                        const headers = { 'Content-Type': 'application/json', 'Accept': 'application/json' };
                        if (csrf) headers['X-CSRF-TOKEN'] = csrf;
                        const response = await fetch('/api/chatbot', { method: 'POST', headers, body: JSON.stringify({ message: messageToSend, mode: 'natural' }) });
                        if (!response.ok) throw new Error('Gagal merespons.');
                        const data = await response.json();
                        const baseReply = data.reply || data.error || 'Maaf, terjadi kesalahan.';
                        let reply = baseReply;
                        const intent = data && typeof data === 'object' ? (data.intent || null) : null;
                        let hasSignals = false;
                        // If structured suggestion exists, do NOT show options bubble in natural mode.
                        // Instead, set a pending flag and guide the user with a concise line.
                        if (data && data.structured && typeof data.structured === 'object') {
                            this.structuredSuggestion = data.structured;
                            const s = this.structuredSuggestion || {};
                            hasSignals = (Array.isArray(s.modules) && s.modules.length) || (Array.isArray(s.wilayah_hits) && s.wilayah_hits.length) || (Array.isArray(s.years) && s.years.length);
                            if (hasSignals) {
                                this.pendingStructured = true;
                                if (this.compactChat) {
                                    reply = `${baseReply} — Ketik \"ya tampilkan\" untuk menampilkan, atau lanjutkan chat bebas.`;
                                } else {
                                    this.renderBotText(baseReply);
                                    this.renderBotText('Ketik "ya tampilkan" bila ingin saya ambilkan hasilnya sekarang, atau lanjutkan chat bebas.');
                                    this.$nextTick(() => this.scrollChatToBottom());
                                    return;
                                }
                            }
                        }
                        // Fallback gating: when backend intent is 'unknown' and no strong structured signals, show guided inline
                        if (intent === 'unknown' && !hasSignals && !this.guidedFallbackShown) {
                            this.guidedFallbackShown = true;
                            this.switchChatMode('guided', { silent: true });
                            this.startGuidedInline();
                            this.$nextTick(() => this.scrollChatToBottom());
                            return;
                        }
                        this.renderBotText(reply);
                    } catch (error) {
                        const msg = (error && error.message) ? String(error.message) : 'Maaf, terjadi kesalahan.';
                        this.renderBotText(msg);
                    } finally {
                        this.isLoading = false;
                        this.$nextTick(() => this.scrollChatToBottom());
                    }
                },
                async handleStructuredMessage(messageToSend) {
                    // Intercept pending typed clarifications first
                    if (this.pendingPrompt && this.pendingPrompt.type === 'klasifikasi') {
                        const raw = String(messageToSend||'');
                        const lower = raw.toLowerCase();
                        const cand = Array.isArray(this.pendingPrompt.candidates)?this.pendingPrompt.candidates:[];
                        const matched = cand.filter(k => lower.includes(String(k.nama||'').toLowerCase()));
                        if (!matched.length) {
                            this.renderBotText('Saya belum mengenali klasifikasi dari teks tersebut. Ketik salah satu nama klasifikasi yang ada.');
                            return;
                        }
                        this.wizard.klasifikasiIds = matched.map(k=>k.id);
                        this.pendingPrompt = null;
                        await this.finishPreview();
                        return;
                    }
                    if (this.pendingPrompt && this.pendingPrompt.type === 'tutorial') {
                        const raw = String(messageToSend||'');
                        const l = raw.trim().toLowerCase();
                        const yes = /^(ya|iya|ok|oke|baik|lanjut|mau|boleh|yes|y)$/i.test(l);
                        const no  = /^(tidak|ga|gak|nggak|enggak|no|n|skip|nanti)$/i.test(l);
                        const pend = this.pendingPrompt.preview;
                        const mod = this.pendingPrompt.module;
                        this.pendingPrompt = null;
                        if (yes) {
                            this.renderBotText(this.buildTutorialText(mod, pend));
                            return;
                        }
                        if (no) {
                            this.renderBotText('Baik, lanjutkan chat bebas.');
                            return;
                        }
                    }
                    // If user confirms to show results and we already have a structured suggestion, execute directly
                    const raw = String(messageToSend || '');
                    const txtLower = raw.trim().toLowerCase();
                    if (this.structuredSuggestion && /\b(tampilkan|ya tampilkan|ok tampilkan|silakan tampilkan|tolong tampilkan)\b/i.test(txtLower)) {
                        this.pendingStructured = false;
                        this.renderBotText('Baik, sedang saya ambilkan datanya...');
                        try { await this.applyStructuredSuggestion(null, { forceCompact: true }); } catch(_) {}
                        this.$nextTick(() => this.scrollChatToBottom());
                        return;
                    }
                    this.isLoading = true;
                    try {
                        const csrfEl = typeof document !== 'undefined' ? document.querySelector('meta[name="csrf-token"]') : null;
                        const csrf = csrfEl ? csrfEl.getAttribute('content') : null;
                        const headers = { 'Content-Type': 'application/json', 'Accept': 'application/json' };
                        if (csrf) headers['X-CSRF-TOKEN'] = csrf;
                        const response = await fetch('/api/chatbot', { method: 'POST', headers, body: JSON.stringify({ message: messageToSend, mode: 'structured' }) });
                        if (!response.ok) throw new Error('Gagal merespons.');
                        const data = await response.json();
                        let reply = data.reply || data.error || 'Maaf, terjadi kesalahan.';

                        // Present structured-first options when available
                        if (data && data.structured && typeof data.structured === 'object') {
                            this.structuredSuggestion = data.structured;
                            const s = this.structuredSuggestion || {};
                            const modules = Array.isArray(s.modules) ? s.modules : [];
                            const wilayahs = Array.isArray(s.wilayah_hits) ? s.wilayah_hits : [];

                            const labelFor = (m) => {
                                const mm = String(m||'').toLowerCase();
                                if (mm === 'benih-pupuk') return 'Benih & Pupuk';
                                if (mm === 'iklim-opt-dpi') return 'Iklim & OPT DPI';
                                return 'Lahan';
                            };

                            if (this.compactChat && (modules.length || wilayahs.length)) {
                                // Compact: avoid options bubble; provide a single hint and set pending
                                this.pendingStructured = true;
                                reply = `${reply} — Ketik \"ya tampilkan\" untuk menampilkan.`;
                            } else if (modules.length || wilayahs.length) {
                                // Non-compact structured mode still avoids bubbles per requirement
                                this.pendingStructured = true;
                                reply = `${reply} — Ketik \"ya tampilkan\" untuk menampilkan.`;
                            }
                        }
                        this.renderBotText(reply);
                    } catch (error) {
                        const msg = (error && error.message) ? String(error.message) : 'Saya belum dapat semua detail. Mau mulai dari modulnya?';
                        this.renderBotText(msg);
                        // Do not push options in structured mode
                    } finally {
                        this.isLoading = false;
                        this.$nextTick(() => this.scrollChatToBottom());
                    }
                },
                async handleGuidedFlow(messageToSend) {
                    // In guided mode, if user types free text (non-command), auto-switch to natural and process it.
                    const raw = String(messageToSend || '');
                    const txt = raw.trim().toLowerCase();
                    if (txt === '') {
                        await this.rePromptCurrentStep();
                        return;
                    }
                    if (txt === '/bebas' || txt === '/natural') {
                        this.switchChatMode('natural');
                        return;
                    }
                    if (txt === '/terstruktur' || txt === '/structured') {
                        this.switchChatMode('structured');
                        return;
                    }
                    // Auto-switch to structured if the text clearly requests to display results,
                    // otherwise switch to natural. This matches the "ya tampilkan" user phrasing.
                    const wantsShow = /\b(tampilkan|show|ya tampilkan|ok tampilkan|silakan tampilkan)\b/i.test(txt);
                    if (wantsShow && this.structuredSuggestion) {
                        this.switchChatMode('structured', { silent: true });
                        await this.handleStructuredMessage(raw);
                    } else {
                        this.switchChatMode('natural', { silent: true });
                        await this.handleNaturalMessage(raw);
                    }
                },
                async sendMessage() {
                    if (!this.userMessage.trim()) return;

                    // Tambahkan pesan user ke histori & kosongkan input
                    this.conversation.push({ sender: 'user', text: this.sanitizeHtml(this.userMessage) });
                    this.$nextTick(() => this.scrollChatToBottom());
                    const messageToSend = this.userMessage;
                    this.userMessage = '';
                    // Route by chat mode
                    if (this.chatMode === 'natural') {
                        await this.handleNaturalMessage(messageToSend);
                    } else if (this.chatMode === 'structured') {
                        await this.handleStructuredMessage(messageToSend);
                    } else {
                        await this.handleGuidedFlow(messageToSend);
                    }
                }
            };
}