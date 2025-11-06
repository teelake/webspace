(function () {
	const endpoint = '/api/track.php';
	const eventEndpoint = '/api/event.php';

	function uuid() {
		return ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g, c =>
			(c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
		);
	}

	function getSessionId() {
		try {
			const key = 'ws_analytics_sid';
			let sid = localStorage.getItem(key);
			if (!sid) { sid = uuid(); localStorage.setItem(key, sid); }
			return sid;
		} catch (_) { return uuid(); }
	}

	function getDeviceType() {
		const ua = navigator.userAgent.toLowerCase();
		const isTablet = /(ipad|tablet|(?!android).*xoom|sch-i800|playbook|silk)|(android(?!.*mobi))/i.test(ua);
		const isMobile = /mobi|iphone|ipod|android.+mobile|windows phone/i.test(ua);
		return { device_type: isTablet ? 'tablet' : (isMobile ? 'mobile' : 'desktop'), is_tablet: isTablet ? 1 : 0, is_mobile: isMobile ? 1 : 0, is_desktop: (!isMobile && !isTablet) ? 1 : 0 };
	}

	function parseUA() {
		const ua = navigator.userAgent;
		let osName = null, osVersion = null, browserName = null, browserVersion = null;
		// OS
		if (/windows nt/i.test(ua)) { osName = 'Windows'; osVersion = (ua.match(/Windows NT ([\d_.]+)/i)||[])[1]||null; }
		else if (/android/i.test(ua)) { osName = 'Android'; osVersion = (ua.match(/Android ([\d_.]+)/i)||[])[1]||null; }
		else if (/iphone|ipad|ipod/i.test(ua)) { osName = 'iOS'; osVersion = (ua.match(/OS ([\d_]+)/i)||[])[1]?.replace(/_/g, '.')||null; }
		else if (/mac os x/i.test(ua)) { osName = 'macOS'; osVersion = (ua.match(/Mac OS X ([\d_]+)/i)||[])[1]?.replace(/_/g, '.')||null; }
		else if (/linux/i.test(ua)) { osName = 'Linux'; }
		// Browser
		if (/edg\//i.test(ua)) { browserName = 'Edge'; browserVersion = (ua.match(/Edg\/([\d.]+)/i)||[])[1]||null; }
		else if (/chrome\//i.test(ua)) { browserName = 'Chrome'; browserVersion = (ua.match(/Chrome\/([\d.]+)/i)||[])[1]||null; }
		else if (/safari/i.test(ua) && /version\//i.test(ua)) { browserName = 'Safari'; browserVersion = (ua.match(/Version\/([\d.]+)/i)||[])[1]||null; }
		else if (/firefox\//i.test(ua)) { browserName = 'Firefox'; browserVersion = (ua.match(/Firefox\/([\d.]+)/i)||[])[1]||null; }
		return { os_name: osName, os_version: osVersion, browser_name: browserName, browser_version: browserVersion };
	}

	function isBot() {
		const ua = navigator.userAgent.toLowerCase();
		return /(bot|crawler|spider|crawling)/i.test(ua) ? 1 : 0;
	}

	let maxScrollPercent = 0;
	function trackScroll() {
		const doc = document.documentElement;
		const body = document.body;
		const scrollTop = window.pageYOffset || doc.scrollTop || body.scrollTop || 0;
		const scrollHeight = Math.max(body.scrollHeight, doc.scrollHeight);
		const clientHeight = doc.clientHeight;
		const total = scrollHeight - clientHeight;
		const percent = total > 0 ? Math.round((scrollTop / total) * 100) : 0;
		if (percent > maxScrollPercent) maxScrollPercent = percent;
	}
	window.addEventListener('scroll', () => { trackScroll(); }, { passive: true });

	const start = Date.now();

	function buildPayload() {
		const dev = getDeviceType();
		const ua = parseUA();
		const conn = (navigator.connection || navigator.mozConnection || navigator.webkitConnection);
		return {
			session_id: getSessionId(),
			user_agent: navigator.userAgent,
			page_url: location.href,
			page_title: document.title,
			referrer_url: document.referrer || null,
			screen_resolution: `${screen.width}x${screen.height}`,
			viewport_size: `${window.innerWidth}x${window.innerHeight}`,
			language: navigator.language || null,
			accept_language: navigator.languages ? navigator.languages.join(',') : null,
			connection_type: conn && conn.effectiveType ? conn.effectiveType : null,
			is_bot: isBot(),
			device_type: dev.device_type,
			is_mobile: dev.is_mobile,
			is_tablet: dev.is_tablet,
			is_desktop: dev.is_desktop,
			os_name: ua.os_name,
			os_version: ua.os_version,
			browser_name: ua.browser_name,
			browser_version: ua.browser_version,
			time_on_page: Math.round((Date.now() - start) / 1000),
			scroll_depth: maxScrollPercent,
			visit_date_local: new Date().toString()
		};
	}

	function send(payload, keepalive) {
		try {
			const body = JSON.stringify(payload);
			if (keepalive && 'sendBeacon' in navigator) {
				const blob = new Blob([body], { type: 'application/json' });
				navigator.sendBeacon(endpoint, blob);
			} else {
				fetch(endpoint, {
					method: 'POST',
					headers: { 'Content-Type': 'application/json' },
					body,
					keepalive: !!keepalive,
					credentials: 'omit'
				}).catch(() => {});
			}
		} catch (_) {}
	}

	function sendEvent(event) {
		try {
			const body = JSON.stringify(event);
			if ('sendBeacon' in navigator) {
				const blob = new Blob([body], { type: 'application/json' });
				navigator.sendBeacon(eventEndpoint, blob);
			} else {
				fetch(eventEndpoint, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body, keepalive: true }).catch(() => {});
			}
		} catch (_) {}
	}

	// initial send after load
	window.addEventListener('load', function() {
		trackScroll();
		setTimeout(() => send(buildPayload(), false), 300);

		// Attach outbound link/download tracking
		document.addEventListener('click', function(e) {
			const a = e.target.closest('a[href]');
			if (!a) return;
			const href = a.getAttribute('href');
			if (!href) return;
			const url = new URL(href, location.href);
			const isOutbound = url.host !== location.host;
			const isDownload = /(\.pdf|\.zip|\.docx?|\.xlsx?|\.pptx?|\.csv|\.mp3|\.mp4)$/i.test(url.pathname);
			if (!isOutbound && !isDownload) return;
			const ev = {
				session_id: getSessionId(),
				page_url: location.href,
				referrer_url: document.referrer || null,
				event_name: isDownload ? 'download' : 'outbound_click',
				event_category: isDownload ? 'engagement' : 'navigation',
				event_label: url.href,
				meta: { text: (a.textContent||'').trim().slice(0,200), target: a.target||null },
				user_agent: navigator.userAgent
			};
			sendEvent(ev);
		}, { passive: true, capture: true });
	});

	// final beacon on unload with final metrics
	window.addEventListener('beforeunload', function() {
		const payload = buildPayload();
		send(payload, true);
	});
})();


