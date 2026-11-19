<header class="w-full px-4 sm:px-6 py-3 flex items-center justify-between z-20 relative shrink-0 bg-white/60 backdrop-blur-xl border-b border-white/20 shadow-sm shadow-black/5">
    
    <!-- Kiri: Hamburger (Mobile Only) + Page Title -->
    <div class="flex items-center gap-3 min-w-0">
        <!-- Hamburger Button — Mobile Only -->
        <button id="btn-hamburger" class="md:hidden w-10 h-10 rounded-xl bg-white/70 hover:bg-white/90 border border-white/40 flex items-center justify-center text-lg text-gray-600 transition-all duration-200 active:scale-90 shrink-0 backdrop-blur-sm" aria-label="Toggle Menu">
            <i class="fa-solid fa-bars"></i>
        </button>
        <button id="btn-sidebar-collapse" class="hidden md:flex w-10 h-10 rounded-xl bg-white/55 hover:bg-white/85 border border-white/40 items-center justify-center text-gray-600 transition-all duration-200 active:scale-90 shrink-0 backdrop-blur-sm" aria-label="Ciutkan sidebar" aria-pressed="false" title="Ciutkan sidebar">
            <i class="fa-solid fa-angles-left text-sm"></i>
        </button>

        <!-- Page Title (set via $page_title variable from controller/view) -->
        <?php if (!empty($page_title)): ?>
        <div class="min-w-0">
            <h1 class="font-bold text-gray-800 truncate" style="font-size: 26px; letter-spacing: -0.4px;"><?= $page_title ?></h1>
            <?php if (!empty($page_subtitle)): ?>
                <p class="mt-0.5 truncate hidden sm:block" style="font-size: 14.5px; color: #6B7280;"><?= $page_subtitle ?></p>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Kanan: Widget Informasi & User -->
    <div class="flex items-center gap-[10px]">
        
        <!-- Status Badge -->
        <?php if (!empty($page_badge)): ?>
            <div class="hidden sm:flex items-center gap-2 bg-emerald-500/10 border border-emerald-500/20 backdrop-blur-sm px-[14px] py-[7px] rounded-full shrink-0">
                <span class="w-[7px] h-[7px] rounded-full bg-[#22C55E]" style="box-shadow: 0 0 0 3px rgba(34,197,94,0.15);"></span>
                <span class="text-[#059669] text-[13px] font-medium leading-none mt-px"><?= $page_badge ?></span>
            </div>
        <?php endif; ?>

        <!-- Notification Bell Widget -->
        <div class="relative">
            <button id="btn-notifications" class="flex items-center justify-center w-[36px] h-[36px] rounded-full bg-white/70 hover:bg-white/90 border border-white/30 transition-all relative text-gray-600 shadow-sm backdrop-blur-sm" aria-label="Notifications">
                <i class="fa-regular fa-bell text-sm"></i>
                <!-- Badge for unread count -->
                <span id="notif-badge" class="hidden absolute -top-1 -right-1 bg-red-500 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full border-2 border-white shadow-sm min-w-[16px] text-center">0</span>
            </button>

            <!-- Notifications Dropdown -->
            <div id="notif-dropdown" class="hidden absolute top-full right-0 sm:-right-24 mt-2 w-72 sm:w-80 bg-white/80 backdrop-blur-2xl rounded-2xl shadow-2xl shadow-black/10 border border-white/30 z-50 text-gray-800 overflow-hidden transform opacity-0 transition-all duration-200 origin-top-right scale-95">
                <div class="bg-indigo-50 px-4 py-3 border-b border-indigo-100 flex justify-between items-center">
                    <h3 class="font-bold text-sm text-indigo-900">Notifikasi</h3>
                    <button id="btn-mark-all-read" class="text-[10px] font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">Tandai semua dibaca</button>
                </div>
                <div id="notif-list" class="max-h-80 overflow-y-auto divide-y divide-gray-50 bg-white">
                    <!-- Notifications will be injected here via JS -->
                    <div class="p-6 text-center text-gray-400 text-xs">
                        <i class="fa-solid fa-spinner fa-spin mb-2 text-lg"></i>
                        <p>Memuat notifikasi...</p>
                    </div>
                </div>
                <div class="bg-gray-50 border-t border-gray-100 px-4 py-2 text-center">
                    <span class="text-[10px] text-gray-400 font-medium">Pembaruan realtime via sistem</span>
                </div>
            </div>
        </div>

        <!-- Pil Tanggal (Bisa di Klik) -->
        <div class="relative">
            <button id="btn-calendar" class="hidden md:flex items-center gap-2 bg-white hover:bg-gray-50 border border-[#E5E7EB] px-[14px] py-[7.5px] rounded-full text-xs font-semibold text-gray-700 transition-colors cursor-pointer select-none shadow-sm">
                <i class="fa-regular fa-calendar"></i> <span id="realtime-date" class="mt-px"><?= date('d M Y') ?></span>
            </button>
            
            <!-- Calendar Dropdown -->
            <div id="calendar-dropdown" class="hidden absolute top-full left-1/2 -translate-x-1/2 mt-2 w-64 bg-white rounded-xl shadow-xl border border-gray-100 z-50 text-gray-800 overflow-hidden transform opacity-0 transition-all duration-200 origin-top scale-95">
                <div class="bg-indigo-50 px-4 py-3 border-b border-indigo-100 flex justify-between items-center">
                    <button id="cal-prev" class="w-6 h-6 rounded-md hover:bg-indigo-200 text-indigo-600 flex items-center justify-center transition-colors"><i class="fa-solid fa-chevron-left text-[10px]"></i></button>
                    <div id="cal-month-year" class="font-bold text-sm text-indigo-900"></div>
                    <button id="cal-next" class="w-6 h-6 rounded-md hover:bg-indigo-200 text-indigo-600 flex items-center justify-center transition-colors"><i class="fa-solid fa-chevron-right text-[10px]"></i></button>
                </div>
                <div class="p-3">
                    <div class="grid grid-cols-7 gap-1 text-center mb-2">
                        <div class="text-[10px] font-bold text-gray-400">Min</div>
                        <div class="text-[10px] font-bold text-gray-400">Sen</div>
                        <div class="text-[10px] font-bold text-gray-400">Sel</div>
                        <div class="text-[10px] font-bold text-gray-400">Rab</div>
                        <div class="text-[10px] font-bold text-gray-400">Kam</div>
                        <div class="text-[10px] font-bold text-gray-400">Jum</div>
                        <div class="text-[10px] font-bold text-gray-400">Sab</div>
                    </div>
                    <div id="cal-days" class="grid grid-cols-7 gap-1 text-center">
                        <!-- Days will be generated by JS -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Pil Profil User & Dropdown -->
        <div class="relative">
            <button id="btn-profile" class="flex items-center gap-2 bg-white hover:bg-gray-50 border border-[#E5E7EB] p-1 sm:pr-[12px] rounded-full text-xs font-semibold text-gray-700 transition-colors focus:outline-none shadow-sm">
                <?php if (session()->get('avatar')): ?>
                    <img src="/uploads/avatars/<?= esc(session()->get('avatar')) ?>" class="w-7 h-7 rounded-full object-cover shadow-sm border border-gray-100" alt="Avatar">
                <?php else: ?>
                    <div class="w-7 h-7 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-white flex items-center justify-center font-bold text-[11px] shadow-sm">
                        <?= substr(session()->get('nama_lengkap') ?? 'A', 0, 1) ?>
                    </div>
                <?php endif; ?>
                <span class="hidden sm:inline px-1 mt-px"><?= session()->get('nama_lengkap') ?? 'Admin' ?></span>
                <i class="fa-solid fa-chevron-down text-[10px] hidden sm:inline-block opacity-50 mt-px"></i>
            </button>
            
            <!-- Profile Dropdown -->
            <div id="profile-dropdown" class="hidden absolute top-full right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 z-50 text-gray-800 overflow-hidden transform opacity-0 transition-all duration-200 origin-top-right scale-95">
                <div class="px-4 py-3 border-b border-gray-100 bg-gray-50/50">
                    <p class="text-[10px] text-gray-500 font-semibold uppercase tracking-wider mb-0.5">Masuk sebagai</p>
                    <p class="text-sm font-bold text-gray-800 truncate"><?= session()->get('nama_lengkap') ?? 'Admin' ?></p>
                </div>
                <div class="p-1.5">
                    <a href="/profile" class="flex items-center gap-2 px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition-colors mb-1">
                        <i class="fa-solid fa-user w-4 text-center"></i> Profil Saya
                    </a>
                    <hr class="border-gray-100 my-1">
                    <a href="/auth/logout" class="flex items-center gap-2 px-3 py-2 text-xs font-semibold text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                        <i class="fa-solid fa-power-off w-4 text-center"></i> Logout
                    </a>
                </div>
            </div>
        </div>

    </div>
</header>

<script>
    // --- Clock & Date Real-time ---
    // --- Real-time Date Only ---
    function updateDate() {
        const now = new Date();
        const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        const day = String(now.getDate()).padStart(2, '0');
        const month = months[now.getMonth()];
        const year = now.getFullYear();
        const dateString = `${day} ${month} ${year}`;
        const dateEl = document.getElementById('realtime-date');
        if (dateEl) dateEl.textContent = dateString;
    }
    updateDate();
    // Update every minute (doesn't need to be every second anymore)
    setInterval(updateDate, 60000);

    // --- Global Dropdown Management ---
    const btnNotif = document.getElementById('btn-notifications');
    const notifDropdown = document.getElementById('notif-dropdown');
    
    const btnCalendar = document.getElementById('btn-calendar');
    const calendarDropdown = document.getElementById('calendar-dropdown');
    
    const btnProfile = document.getElementById('btn-profile');
    const profileDropdown = document.getElementById('profile-dropdown');

    function closeAllDropdowns() {
        if (notifDropdown && !notifDropdown.classList.contains('hidden')) {
            notifDropdown.classList.add('opacity-0', 'scale-95');
            notifDropdown.classList.remove('opacity-100', 'scale-100');
            setTimeout(() => notifDropdown.classList.add('hidden'), 200);
        }
        if (calendarDropdown && !calendarDropdown.classList.contains('hidden')) {
            calendarDropdown.classList.add('opacity-0', 'scale-95');
            calendarDropdown.classList.remove('opacity-100', 'scale-100');
            setTimeout(() => calendarDropdown.classList.add('hidden'), 200);
        }
        if (profileDropdown && !profileDropdown.classList.contains('hidden')) {
            profileDropdown.classList.add('opacity-0', 'scale-95');
            profileDropdown.classList.remove('opacity-100', 'scale-100');
            setTimeout(() => profileDropdown.classList.add('hidden'), 200);
        }
    }

    // Toggle Profile
    if (btnProfile && profileDropdown) {
        btnProfile.addEventListener('click', (e) => {
            e.stopPropagation();
            if (profileDropdown.classList.contains('hidden')) {
                closeAllDropdowns();
                profileDropdown.classList.remove('hidden');
                setTimeout(() => {
                    profileDropdown.classList.remove('opacity-0', 'scale-95');
                    profileDropdown.classList.add('opacity-100', 'scale-100');
                }, 10);
            } else {
                closeAllDropdowns();
            }
        });
    }

    // Toggle Notifications
    if (btnNotif && notifDropdown) {
        btnNotif.addEventListener('click', (e) => {
            e.stopPropagation();
            if (notifDropdown.classList.contains('hidden')) {
                closeAllDropdowns();
                notifDropdown.classList.remove('hidden');
                setTimeout(() => {
                    notifDropdown.classList.remove('opacity-0', 'scale-95');
                    notifDropdown.classList.add('opacity-100', 'scale-100');
                }, 10);
                fetchNotifications(); // Refresh on open
            } else {
                closeAllDropdowns();
            }
        });
    }

    // Toggle Calendar
    if (btnCalendar && calendarDropdown) {
        btnCalendar.addEventListener('click', (e) => {
            e.stopPropagation();
            if (calendarDropdown.classList.contains('hidden')) {
                closeAllDropdowns();
                calendarDropdown.classList.remove('hidden');
                setTimeout(() => {
                    calendarDropdown.classList.remove('opacity-0', 'scale-95');
                    calendarDropdown.classList.add('opacity-100', 'scale-100');
                }, 10);
                renderCalendar(currentDate);
            } else {
                closeAllDropdowns();
            }
        });
    }

    // Close when clicking outside
    document.addEventListener('click', (e) => {
        const isOutsideNotif = notifDropdown && !notifDropdown.contains(e.target) && btnNotif && !btnNotif.contains(e.target);
        const isOutsideCal = calendarDropdown && !calendarDropdown.contains(e.target) && btnCalendar && !btnCalendar.contains(e.target);
        const isOutsideProf = profileDropdown && !profileDropdown.contains(e.target) && btnProfile && !btnProfile.contains(e.target);
        
        if (isOutsideNotif && isOutsideCal && isOutsideProf) {
            closeAllDropdowns();
        }
    });

    // --- Notification System UI ---
    const notifBadge = document.getElementById('notif-badge');
    const notifList = document.getElementById('notif-list');
    const btnMarkAllRead = document.getElementById('btn-mark-all-read');

    function timeAgo(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const seconds = Math.floor((now - date) / 1000);
        
        let interval = Math.floor(seconds / 31536000);
        if (interval > 1) return interval + " thn";
        interval = Math.floor(seconds / 2592000);
        if (interval > 1) return interval + " bln";
        interval = Math.floor(seconds / 86400);
        if (interval >= 1) return interval + " hr";
        interval = Math.floor(seconds / 3600);
        if (interval >= 1) return interval + " jam";
        interval = Math.floor(seconds / 60);
        if (interval >= 1) return interval + " mnt";
        return Math.floor(seconds) + " dtk";
    }

    function renderNotifications(data) {
        if (!data || data.length === 0) {
            notifList.innerHTML = `
                <div class="p-6 text-center text-gray-400 flex flex-col items-center">
                    <div class="w-10 h-10 bg-gray-50 rounded-full flex items-center justify-center mb-2">
                        <i class="fa-regular fa-bell-slash text-gray-300"></i>
                    </div>
                    <p class="text-xs font-medium">Tidak ada notifikasi baru</p>
                </div>
            `;
            return;
        }

        notifList.innerHTML = data.map(n => `
            <div class="p-3 hover:bg-slate-50 transition-colors cursor-pointer group flex gap-3 items-start border-l-2 ${n.is_read == 0 ? 'border-indigo-500 bg-indigo-50/30' : 'border-transparent'}" onclick="handleNotifClick(${n.id}, '${n.link}')">
                <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center shrink-0 mt-0.5">
                    <i class="fa-solid fa-bell text-xs"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-bold text-gray-800 leading-tight mb-1 truncate">${n.title}</p>
                    <p class="text-[11px] text-gray-500 leading-snug line-clamp-2">${n.message}</p>
                    <p class="text-[9px] text-gray-400 mt-1.5 flex items-center gap-1">
                        <i class="fa-regular fa-clock"></i> ${timeAgo(n.created_at)}
                    </p>
                </div>
                <div class="w-2 h-2 rounded-full bg-indigo-500 mt-1.5 opacity-0 ${n.is_read == 0 ? 'group-hover:opacity-100' : ''} transition-opacity" title="Tandai dibaca"></div>
            </div>
        `).join('');
    }

    function fetchNotifications() {
        fetch('/notifications/get')
            .then(res => res.json())
            .then(res => {
                if(res.status === 'success') {
                    // Update Badge
                    if(res.count > 0) {
                        notifBadge.textContent = res.count > 99 ? '99+' : res.count;
                        notifBadge.classList.remove('hidden');
                    } else {
                        notifBadge.classList.add('hidden');
                    }
                    // Update List
                    renderNotifications(res.notifications);
                }
            })
            .catch(err => console.error('Failed to fetch notifications', err));
    }

    window.handleNotifClick = function(id, link) {
        // Optimistic UI update
        // Fetch to mark read
        fetch(`/notifications/mark_read/${id}`, { method: 'POST' })
            .then(() => {
                if (link && link !== 'null' && link !== '') {
                    window.location.href = link;
                } else {
                    fetchNotifications();
                }
            });
    }

    if (btnMarkAllRead) {
        btnMarkAllRead.addEventListener('click', () => {
            fetch('/notifications/mark_all_read', { method: 'POST' })
                .then(res => res.json())
                .then(res => {
                    if(res.status === 'success') {
                        fetchNotifications();
                    }
                });
        });
    }

    // Initial fetch and poll every 30 seconds
    if (btnNotif) {
        fetchNotifications();
        setInterval(fetchNotifications, 30000);
    }

    // --- Interactive Mini Calendar ---
    let currentDate = new Date(); // Tracks the currently viewed month

    // Calendar rendering logic
    function renderCalendar(date) {
        const year = date.getFullYear();
        const month = date.getMonth();
        const today = new Date();

        const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        document.getElementById('cal-month-year').textContent = `${monthNames[month]} ${year}`;

        const firstDay = new Date(year, month, 1).getDay(); // 0 (Sun) to 6 (Sat)
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const daysInPrevMonth = new Date(year, month, 0).getDate();

        const daysContainer = document.getElementById('cal-days');
        daysContainer.innerHTML = '';

        // Previous month days
        for (let i = firstDay - 1; i >= 0; i--) {
            const dayNum = daysInPrevMonth - i;
            daysContainer.innerHTML += `<div class="text-xs py-1.5 text-gray-300">${dayNum}</div>`;
        }

        // Current month days
        for (let i = 1; i <= daysInMonth; i++) {
            const isToday = i === today.getDate() && month === today.getMonth() && year === today.getFullYear();
            const styles = isToday 
                ? 'bg-indigo-600 text-white rounded-md font-bold shadow-sm' 
                : 'text-gray-700 hover:bg-gray-100 rounded-md cursor-pointer transition-colors';
            daysContainer.innerHTML += `<div class="text-xs py-1.5 ${styles}">${i}</div>`;
        }

        // Next month days to fill grid
        const totalCells = firstDay + daysInMonth;
        const nextDays = Math.ceil(totalCells / 7) * 7 - totalCells;
        for (let i = 1; i <= nextDays; i++) {
            daysContainer.innerHTML += `<div class="text-xs py-1.5 text-gray-300">${i}</div>`;
        }
    }

    // Month navigation
    document.getElementById('cal-prev').addEventListener('click', (e) => {
        e.stopPropagation();
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar(currentDate);
    });
    
    document.getElementById('cal-next').addEventListener('click', (e) => {
        e.stopPropagation();
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar(currentDate);
    });
</script>