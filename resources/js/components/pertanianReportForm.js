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
                            // If conversation empty or has no interactive bubble, reset to guided welcome
                            const hasInteractive = Array.isArray(this.conversation) && this.conversation.some(c => c && (c.type === 'options' || c.type === 'checklist'));
                            if (!this.conversation || this.conversation.length === 0 || !hasInteractive) {
                                this.resetGuidedChat();
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

                    // Initialize guided chatbot flow
                    this.resetGuidedChat();
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
                isLoading: false,
                userMessage: '',
                conversation: [],
                showChatResetConfirm: false,
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
                    { sender: 'bot', type: 'text', text: 'Selamat datang! Data apa yang ingin Anda cari?' },
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
                            const html = `<div><div><strong>Variabel:</strong> ${nama}${satuan}</div><div class="mt-1 text-neutral-700">${desc}</div></div>`;
                            this.conversation.push({ sender:'bot', type:'text', text: html });
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
                const bulans = (this.wizardData.bulans || []).map(b => ({ value: b.id, label: b.nama }));
                this.conversation.push({ sender:'bot', type:'checklist', title:'Pilih Bulan', options: bulans, selected: [] });
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
                // if same as page module and wizardData already has topiks, skip
                if ((module === this.moduleType) && (this.wizardData.topiks && this.wizardData.topiks.length)) {
                    // ensure years/bulans present
                    return;
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
                const res = await fetch(`/api/${module}/klasifikasis`, { method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')}, body: JSON.stringify({ variabel_ids: [variabelId] })});
                const list = await res.json();
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
                try {
                    this.isLoading = true;
                    const res = await fetch(`/pertanian/${this.wizard.moduleType}/filter`, {
                        method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                        body: JSON.stringify({ selections, config })
                    });
                    const data = await res.json();
                    if (!res.ok) throw new Error(data?.message || 'Gagal memuat pratinjau');
                    // Build minimal meta text for preview
                    const topikObj = (this.wizardData.topiks || []).find(t => String(t.id) === String(this.wizard.topikId));
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
                    // Ask user which preview style they want
                    this.conversation.push({ sender:'bot', type:'options', title:'Tampilkan hasil sebagai?', options:[
                        { value:'table', label:'Tabel' }, { value:'summary', label:'Ringkasan' }
                    ]});
                    this.wizard._pendingPreview = { results: tablePayload, meta, selections, config, moduleType: this.wizard.moduleType };
                    this.wizard.step = 'choose_preview_style';
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
                if (style === 'table') {
                    this.conversation.push({ sender:'bot', type:'table', title:'Pratinjau Hasil', results: pend.results, meta: pend.meta, payload: pend });
                } else {
                    const lines = this.buildSummaryLines(pend.results);
                    this.conversation.push({ sender:'bot', type:'summary', title:'Ringkasan', summaryLines: lines, meta: pend.meta, payload: pend });
                }
                this.wizard.step = 'preview';
                    this.conversation.push({ sender:'bot', type:'options', title:'Ingin dibantu lagi?', options:[
                        { value: 'yes', label: 'Ya, lanjut pandu' },
                        { value: 'no', label: 'Tidak, saya ketik saja' },
                    ]});
                    this.wizard.step = 'post_preview_help';
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
                // Offer to continue guided assistance
                this.conversation.push({ sender:'bot', type:'options', title:'Ingin dibantu lagi?', options:[
                    { value: 'yes', label: 'Ya, lanjut pandu' },
                    { value: 'no', label: 'Tidak, saya ketik saja' },
                ]});
                this.wizard.step = 'post_preview_help';
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
                async sendMessage() {
                    if (!this.userMessage.trim()) return;

                    // Tambahkan pesan user ke histori & kosongkan input
                    this.conversation.push({ sender: 'user', text: this.userMessage });
                    this.$nextTick(() => this.scrollChatToBottom());
                    const messageToSend = this.userMessage;
                    this.userMessage = '';
                    this.isLoading = true;

                    try {
                        const response = await fetch('/api/chatbot', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ message: messageToSend })
                        });
                        
                        if (!response.ok) throw new Error('Gagal merespons.');

                        const data = await response.json();
                        // Tambahkan balasan bot ke histori (backend now returns simple text only)
                        this.conversation.push({ sender: 'bot', text: data.reply || data.error || 'Maaf, terjadi kesalahan.' });
                        this.$nextTick(() => this.scrollChatToBottom());

                    } catch (error) {
                        this.conversation.push({ sender: 'bot', text: 'Maaf, terjadi kesalahan. Coba lagi nanti.' });
                        this.$nextTick(() => this.scrollChatToBottom());
                    } finally {
                        this.isLoading = false;
                    }
                }
            };
}