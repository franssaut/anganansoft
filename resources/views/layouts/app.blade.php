<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name', 'AngananSoft')) - {{ \App\Models\Setting::get('site_name', 'AngananSoft') }}</title>
    <meta name="description" content="@yield('meta_description', \App\Models\Setting::get('site_description', 'Download software multimedia,tools,game,windows,IDM untuk PC|Komputer Full Version.'))">
    <meta name="keywords" content="@yield('meta_keywords', \App\Models\Setting::get('site_keywords', 'Download Software PC Komputer, Game, android, IPhone Full Version'))">
    <meta property="og:title" content="@yield('title', 'AngananSoft')">
    <meta property="og:description" content="@yield('meta_description', \App\Models\Setting::get('site_description', 'Download software multimedia...'))">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Oswald:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        :root {
            --primary-color: #26c6da;
            --primary-dark: #00acc1;
            --dark-color: #222222;
            --light-bg: #f5f6f8;
            --card-bg: #ffffff;
            --border-color: #e5e7eb;
            --text-color: #374151;
            --text-muted: #6b7280;
            --text-heading: #111827;
            --font-main: 'Open Sans', system-ui, -apple-system, sans-serif;
            --font-heading: 'Oswald', sans-serif;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-main);
            background-color: var(--light-bg);
            color: var(--text-color);
            line-height: 1.6;
            font-size: 14.5px;
        }

        a {
            color: var(--primary-color);
            text-decoration: none;
            transition: color 0.2s ease, background 0.2s ease;
        }

        a:hover {
            color: var(--primary-dark);
        }

        .container {
            max-width: 1140px;
            margin: 0 auto;
            padding: 0 15px;
        }

        /* Top Bar */
        .top-bar {
            background-color: var(--dark-color);
            color: #ccc;
            font-size: 12px;
            border-bottom: 2px solid var(--primary-color);
        }

        .top-bar-inner {
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 38px;
        }

        .top-menu {
            display: flex;
            list-style: none;
            gap: 15px;
            align-items: center;
        }

        .top-menu a {
            color: #ddd;
            font-size: 12px;
            text-transform: uppercase;
            font-weight: 600;
        }

        .top-menu a:hover {
            color: var(--primary-color);
        }

        .top-social {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .top-social a {
            color: #ddd;
            width: 26px;
            height: 26px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 3px;
            background: rgba(255, 255, 255, 0.1);
            font-size: 13px;
        }

        .top-social a:hover {
            color: #fff;
            background: var(--primary-color);
        }

        .admin-link {
            background: var(--primary-color) !important;
            color: #fff !important;
            padding: 3px 10px;
            border-radius: 3px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        /* Main Header */
        .header {
            background: #fff;
            padding: 22px 0;
            border-bottom: 1px solid var(--border-color);
        }

        .header-inner {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .logo-box {
            display: flex;
            flex-direction: column;
        }

        .site-title {
            font-family: var(--font-heading);
            font-size: 38px;
            font-weight: 700;
            letter-spacing: -0.5px;
            color: #111;
            text-transform: uppercase;
            line-height: 1;
        }

        .site-title span {
            color: var(--primary-color);
        }

        .site-tagline {
            font-size: 11.5px;
            color: var(--text-muted);
            margin-top: 6px;
            max-width: 480px;
            line-height: 1.4;
        }

        .header-banner {
            background: linear-gradient(135deg, #1e293b, #0f172a);
            color: #fff;
            padding: 12px 20px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            gap: 15px;
            border-left: 4px solid var(--primary-color);
        }

        .header-banner i {
            font-size: 28px;
            color: var(--primary-color);
        }

        .banner-text h4 {
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 2px;
            color: #fff;
        }

        .banner-text p {
            font-size: 11px;
            color: #94a3b8;
        }

        /* Navigation Bar */
        .main-nav {
            background: var(--primary-color);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-inner {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .menu-list {
            display: flex;
            list-style: none;
            flex-wrap: wrap;
        }

        .menu-list > li > a {
            display: block;
            padding: 14px 18px;
            color: #ffffff;
            font-weight: 700;
            font-size: 13.5px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            transition: all 0.2s;
        }

        .menu-list > li > a:hover,
        .menu-list > li.active > a {
            background-color: rgba(0, 0, 0, 0.15);
            color: #ffffff;
        }

        .search-form-nav {
            display: flex;
            align-items: center;
        }

        .search-form-nav input {
            padding: 8px 12px;
            border: none;
            border-radius: 4px 0 0 4px;
            font-size: 13px;
            outline: none;
            width: 170px;
            transition: width 0.3s;
        }

        .search-form-nav input:focus {
            width: 220px;
        }

        .search-form-nav button {
            background: var(--dark-color);
            color: #fff;
            border: none;
            padding: 8px 14px;
            border-radius: 0 4px 4px 0;
            cursor: pointer;
            transition: background 0.2s;
        }

        .search-form-nav button:hover {
            background: #000;
        }

        /* Breaking Ticker */
        .ticker-section {
            background: #fff;
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 25px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.03);
        }

        .ticker-inner {
            display: flex;
            align-items: center;
            overflow: hidden;
            height: 42px;
        }

        .ticker-badge {
            background: #e91e63;
            color: #fff;
            font-family: var(--font-heading);
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.5px;
            padding: 0 16px;
            height: 100%;
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
            text-transform: uppercase;
        }

        .ticker-badge i {
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }

        .ticker-content {
            padding-left: 15px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: flex;
            gap: 20px;
        }

        .ticker-content a {
            color: #333;
            font-size: 13px;
            font-weight: 600;
            transition: color 0.2s;
        }

        .ticker-content a:hover {
            color: var(--primary-color);
        }

        /* Layout Grid */
        .main-layout {
            display: grid;
            grid-template-columns: 1fr 330px;
            gap: 25px;
            margin-bottom: 40px;
        }

        /* Post Card (Sora Seo Style) */
        .post-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 6px;
            margin-bottom: 22px;
            overflow: hidden;
            display: flex;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .post-card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transform: translateY(-2px);
        }

        .post-thumbnail-wrapper {
            width: 260px;
            min-height: 190px;
            position: relative;
            flex-shrink: 0;
            overflow: hidden;
            background: #eee;
        }

        .post-thumbnail {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
            display: block;
        }

        .post-card:hover .post-thumbnail {
            transform: scale(1.06);
        }

        .category-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            background: var(--primary-color);
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            padding: 3px 9px;
            border-radius: 3px;
            z-index: 2;
            letter-spacing: 0.5px;
        }

        .post-body {
            padding: 18px 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            flex-grow: 1;
        }

        .post-title {
            font-size: 19px;
            font-weight: 700;
            line-height: 1.35;
            margin-bottom: 10px;
        }

        .post-title a {
            color: var(--text-heading);
            transition: color 0.2s;
        }

        .post-title a:hover {
            color: var(--primary-color);
        }

        .post-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            font-size: 12px;
            color: var(--text-muted);
            margin-bottom: 12px;
        }

        .post-meta span {
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .post-meta i {
            color: var(--primary-color);
        }

        .post-excerpt {
            color: #4b5563;
            font-size: 13.5px;
            line-height: 1.55;
            margin-bottom: 15px;
            flex-grow: 1;
        }

        .post-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #f1f5f9;
            padding-top: 12px;
        }

        .read-more-btn {
            background: var(--primary-color);
            color: #fff;
            padding: 6px 14px;
            font-size: 12.5px;
            font-weight: 600;
            border-radius: 4px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: background 0.2s;
        }

        .read-more-btn:hover {
            background: var(--primary-dark);
            color: #fff;
        }

        .social-share-mini {
            display: flex;
            gap: 6px;
        }

        .share-icon-btn {
            width: 28px;
            height: 28px;
            border-radius: 3px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 12px;
            transition: opacity 0.2s;
        }

        .share-icon-btn:hover {
            opacity: 0.85;
            color: #fff;
        }

        .share-fb { background: #3b5998; }
        .share-tw { background: #1da1f2; }
        .share-wa { background: #25d366; }

        /* Sidebar Widgets */
        .sidebar {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .widget {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 6px;
            overflow: hidden;
        }

        .widget-title {
            font-family: var(--font-heading);
            font-size: 16px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 12px 18px;
            background: #fff;
            border-bottom: 2px solid var(--primary-color);
            color: var(--text-heading);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .widget-content {
            padding: 18px;
        }

        /* Search Widget */
        .search-widget-form {
            display: flex;
        }

        .search-widget-form input {
            flex-grow: 1;
            padding: 10px 12px;
            border: 1px solid var(--border-color);
            border-radius: 4px 0 0 4px;
            font-size: 13.5px;
            outline: none;
        }

        .search-widget-form input:focus {
            border-color: var(--primary-color);
        }

        .search-widget-form button {
            background: var(--primary-color);
            color: #fff;
            border: none;
            padding: 0 16px;
            border-radius: 0 4px 4px 0;
            cursor: pointer;
            font-weight: 600;
        }

        /* Popular Posts Widget */
        .popular-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .popular-item {
            display: flex;
            gap: 12px;
            align-items: center;
            position: relative;
        }

        .popular-thumb-wrap {
            width: 70px;
            height: 60px;
            border-radius: 4px;
            overflow: hidden;
            flex-shrink: 0;
            position: relative;
        }

        .popular-thumb {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .popular-rank {
            position: absolute;
            top: 0;
            left: 0;
            background: var(--primary-color);
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom-right-radius: 4px;
        }

        .popular-info h5 {
            font-size: 13.5px;
            font-weight: 600;
            line-height: 1.3;
            margin-bottom: 4px;
        }

        .popular-info h5 a {
            color: var(--text-heading);
        }

        .popular-info h5 a:hover {
            color: var(--primary-color);
        }

        .popular-info .date {
            font-size: 11.5px;
            color: var(--text-muted);
        }

        /* Categories List */
        .cat-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .cat-list li a {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 12px;
            background: #f8fafc;
            border-radius: 4px;
            color: #334155;
            font-size: 13px;
            font-weight: 600;
            border-left: 3px solid var(--primary-color);
            transition: all 0.2s;
        }

        .cat-list li a:hover {
            background: #e2f7f9;
            color: var(--primary-dark);
            padding-left: 15px;
        }

        .cat-list .count {
            background: #fff;
            color: var(--text-muted);
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 11px;
            border: 1px solid var(--border-color);
        }

        /* Tags Cloud */
        .tags-cloud {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .tag-pill {
            background: #f1f5f9;
            color: #475569;
            padding: 4px 10px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .tag-pill:hover {
            background: var(--primary-color);
            color: #fff;
        }

        /* Featured Sidebar Box */
        .featured-widget-card {
            border-radius: 4px;
            overflow: hidden;
            position: relative;
        }

        .featured-widget-card img {
            width: 100%;
            height: 160px;
            object-fit: cover;
            display: block;
        }

        .featured-widget-overlay {
            padding: 12px 14px;
            background: #fff;
        }

        .featured-widget-overlay h4 {
            font-size: 14px;
            font-weight: 700;
            line-height: 1.35;
            margin-bottom: 6px;
        }

        /* Footer */
        .footer {
            background: #1a202c;
            color: #a0aec0;
            padding-top: 45px;
            border-top: 4px solid var(--primary-color);
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr 1fr;
            gap: 35px;
            margin-bottom: 35px;
        }

        .footer-col h4 {
            font-family: var(--font-heading);
            color: #fff;
            font-size: 18px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 18px;
            position: relative;
            padding-bottom: 10px;
            border-bottom: 2px solid rgba(255, 255, 255, 0.1);
        }

        .footer-col h4::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 40px;
            height: 2px;
            background: var(--primary-color);
        }

        .footer-col p {
            font-size: 13.5px;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .footer-links {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .footer-links a {
            color: #cbd5e0;
            font-size: 13px;
            transition: color 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .footer-links a:hover {
            color: var(--primary-color);
            padding-left: 4px;
        }

        .footer-bottom {
            background: #111827;
            padding: 18px 0;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            font-size: 12.5px;
        }

        .footer-bottom-inner {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        /* Pagination */
        .pagination-wrap {
            margin: 30px 0;
            display: flex;
            justify-content: center;
        }

        .pagination-list {
            display: flex;
            gap: 6px;
            list-style: none;
        }

        .pagination-list li a,
        .pagination-list li span {
            padding: 8px 14px;
            background: #fff;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            color: var(--text-color);
            font-weight: 600;
            font-size: 13px;
        }

        .pagination-list li.active span {
            background: var(--primary-color);
            color: #fff;
            border-color: var(--primary-color);
        }

        .pagination-list li a:hover {
            background: #f1f5f9;
        }

        /* Single Post Detail Styling */
        .single-post-card {
            background: #fff;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            padding: 28px;
            margin-bottom: 30px;
        }

        .breadcrumb {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            font-size: 12.5px;
            color: var(--text-muted);
            margin-bottom: 16px;
            align-items: center;
        }

        .breadcrumb a {
            color: #4b5563;
        }

        .breadcrumb a:hover {
            color: var(--primary-color);
        }

        .single-title {
            font-size: 28px;
            font-weight: 800;
            color: var(--text-heading);
            line-height: 1.3;
            margin-bottom: 14px;
        }

        .single-featured-image {
            width: 100%;
            max-height: 440px;
            object-fit: cover;
            border-radius: 6px;
            margin: 20px 0;
            border: 1px solid var(--border-color);
        }

        .post-content-body {
            font-size: 15px;
            line-height: 1.75;
            color: #2d3748;
        }

        .post-content-body p {
            margin-bottom: 16px;
        }

        .post-content-body h2,
        .post-content-body h3 {
            color: #1a202c;
            margin: 24px 0 12px 0;
            font-family: var(--font-heading);
        }

        .post-content-body ul,
        .post-content-body ol {
            margin-left: 24px;
            margin-bottom: 18px;
        }

        .post-content-body li {
            margin-bottom: 6px;
        }

        /* Download Specification Box (AngananSoft signature) */
        .download-box {
            background: #f8fafc;
            border: 2px dashed var(--primary-color);
            border-radius: 8px;
            padding: 22px;
            margin: 30px 0;
        }

        .download-box-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 18px;
            padding-bottom: 12px;
            border-bottom: 1px solid #e2e8f0;
        }

        .download-box-header i {
            font-size: 26px;
            color: var(--primary-color);
        }

        .download-box-header h3 {
            font-size: 19px;
            color: #1e293b;
            font-weight: 700;
        }

        .specs-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 13.5px;
        }

        .specs-table td {
            padding: 9px 12px;
            border-bottom: 1px solid #edf2f7;
        }

        .specs-table td:first-child {
            width: 32%;
            font-weight: 700;
            color: #475569;
            background: #f1f5f9;
            border-radius: 3px;
        }

        .password-badge {
            background: #fef3c7;
            color: #92400e;
            padding: 3px 10px;
            font-weight: 700;
            border-radius: 4px;
            font-family: monospace;
            font-size: 14px;
            border: 1px solid #fde68a;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-copy {
            background: #f59e0b;
            color: #fff;
            border: none;
            padding: 2px 7px;
            border-radius: 3px;
            cursor: pointer;
            font-size: 11px;
        }

        .download-buttons-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 12px;
            margin-top: 15px;
        }

        .download-btn {
            background: linear-gradient(135deg, #26c6da, #00acc1);
            color: #fff;
            padding: 12px 18px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: all 0.2s;
            text-align: center;
        }

        .download-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px -1px rgba(0, 0, 0, 0.15);
            color: #fff;
        }

        .download-btn.mirror-2 {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        }

        .download-btn.mirror-3 {
            background: linear-gradient(135deg, #10b981, #047857);
        }

        /* Instructions Accordion / Card */
        .info-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 18px;
            margin-top: 18px;
        }

        .info-card h4 {
            font-size: 15px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-card pre {
            background: #f8fafc;
            padding: 12px;
            border-radius: 4px;
            font-family: inherit;
            white-space: pre-line;
            font-size: 13.5px;
            color: #334155;
            border-left: 3px solid var(--primary-color);
        }

        /* Related Posts */
        .related-section {
            margin-top: 35px;
            padding-top: 25px;
            border-top: 1px solid var(--border-color);
        }

        .related-title {
            font-family: var(--font-heading);
            font-size: 20px;
            text-transform: uppercase;
            margin-bottom: 18px;
            color: #1e293b;
        }

        .related-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .related-item {
            background: #f8fafc;
            border-radius: 6px;
            overflow: hidden;
            border: 1px solid var(--border-color);
            transition: transform 0.2s;
        }

        .related-item:hover {
            transform: translateY(-3px);
        }

        .related-item img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            display: block;
        }

        .related-item-body {
            padding: 12px;
        }

        .related-item-body h5 {
            font-size: 13.5px;
            line-height: 1.35;
        }

        .related-item-body h5 a {
            color: #1e293b;
        }

        .related-item-body h5 a:hover {
            color: var(--primary-color);
        }

        /* Comments Section */
        .comments-section {
            background: #fff;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            padding: 25px;
            margin-top: 30px;
        }

        .comments-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-bottom: 30px;
        }

        .comment-item {
            display: flex;
            gap: 14px;
            padding-bottom: 15px;
            border-bottom: 1px solid #f1f5f9;
        }

        .comment-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            font-weight: 700;
            font-size: 16px;
            flex-shrink: 0;
        }

        .comment-content {
            flex-grow: 1;
        }

        .comment-author-name {
            font-weight: 700;
            font-size: 14px;
            color: #1e293b;
        }

        .comment-date {
            font-size: 11.5px;
            color: var(--text-muted);
            margin-bottom: 6px;
        }

        .comment-text {
            font-size: 13.5px;
            color: #475569;
        }

        .comment-form-group {
            margin-bottom: 14px;
        }

        .comment-form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 5px;
            color: #334155;
        }

        .comment-form-group input,
        .comment-form-group textarea {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-family: inherit;
            font-size: 13.5px;
            outline: none;
        }

        .comment-form-group input:focus,
        .comment-form-group textarea:focus {
            border-color: var(--primary-color);
        }

        .btn-submit-comment {
            background: var(--primary-color);
            color: #fff;
            padding: 10px 22px;
            border: none;
            border-radius: 4px;
            font-weight: 700;
            font-size: 13.5px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-submit-comment:hover {
            background: var(--primary-dark);
        }

        /* Alert notifications */
        .alert-success {
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
            padding: 12px 16px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 13.5px;
        }

        /* Responsive */
        @media (max-width: 900px) {
            .main-layout {
                grid-template-columns: 1fr;
            }
            .header-banner {
                display: none;
            }
            .footer-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 600px) {
            .post-card {
                flex-direction: column;
            }
            .post-thumbnail-wrapper {
                width: 100%;
                height: 200px;
            }
            .top-bar-inner {
                flex-direction: column;
                height: auto;
                padding: 6px 0;
                gap: 6px;
            }
            .menu-list {
                justify-content: center;
            }
            .search-form-nav {
                width: 100%;
                padding: 8px 12px;
            }
            .search-form-nav input {
                flex-grow: 1;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container top-bar-inner">
            <ul class="top-menu">
                <li><a href="{{ route('blog.index') }}">Beranda</a></li>
                <li><a href="{{ route('blog.page', 'tentang-kami') }}">Tentang Kami</a></li>
                <li><a href="{{ route('blog.page', 'kontak') }}">Kontak</a></li>
                <li><a href="{{ route('blog.page', 'kebijakan-privasi') }}">Kebijakan Privasi</a></li>
                <li><a href="{{ route('blog.page', 'disclaimer') }}">Disclaimer</a></li>
            </ul>
            <div class="top-social">
                <a href="{{ \App\Models\Setting::get('facebook_url', '#') }}" target="_blank" title="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="{{ \App\Models\Setting::get('twitter_url', '#') }}" target="_blank" title="Twitter"><i class="fa-brands fa-x-twitter"></i></a>
                <a href="{{ \App\Models\Setting::get('telegram_url', '#') }}" target="_blank" title="Telegram"><i class="fa-brands fa-telegram"></i></a>
                <a href="{{ route('admin.login') }}" class="admin-link" title="Dashboard Admin"><i class="fa-solid fa-lock"></i> Admin</a>
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <header class="header">
        <div class="container header-inner">
            <div class="logo-box">
                <a href="{{ route('blog.index') }}" class="site-title">
                    Anganan<span>Soft</span>
                </a>
                <p class="site-tagline">
                    {{ \App\Models\Setting::get('site_tagline', 'Download software multimedia, tools, game, windows, IDM untuk PC|Android|Iphone. Full Version|Premium.') }}
                </p>
            </div>
            <div class="header-banner">
                <i class="fa-solid fa-shield-halved"></i>
                <div class="banner-text">
                    <h4>100% Bebas Virus & Teruji</h4>
                    <p>Semua software & game diverifikasi bebas malware dan siap pakai.</p>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Navigation Ribbon -->
    <nav class="main-nav">
        <div class="container nav-inner">
            <ul class="menu-list">
                <li class="{{ request()->routeIs('blog.index') && !request('q') && !request('search') ? 'active' : '' }}">
                    <a href="{{ route('blog.index') }}"><i class="fa-solid fa-house"></i> Beranda</a>
                </li>
                @foreach(\App\Models\Category::all() as $cat)
                <li class="{{ request()->is('category/' . $cat->slug) ? 'active' : '' }}">
                    <a href="{{ route('blog.category', $cat->slug) }}">{{ $cat->name }}</a>
                </li>
                @endforeach
            </ul>
            <form action="{{ route('blog.index') }}" method="GET" class="search-form-nav">
                <input type="text" name="q" placeholder="Cari software, game..." value="{{ request('q') ?? request('search') }}">
                <button type="submit" title="Cari"><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>
        </div>
    </nav>

    <!-- Breaking News Ticker -->
    @if(isset($breakingPosts) && $breakingPosts->count() > 0)
    <div class="ticker-section">
        <div class="container ticker-inner">
            <div class="ticker-badge">
                <i class="fa-solid fa-bolt"></i> Update Terbaru
            </div>
            <div class="ticker-content">
                @foreach($breakingPosts as $bPost)
                    <a href="{{ route('blog.show', $bPost->slug) }}">
                        <i class="fa-solid fa-angle-right" style="color:var(--primary-color)"></i> {{ $bPost->title }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Main Content Area -->
    <main class="container">
        @if(session('success'))
            <div class="alert-success">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif

        <div class="main-layout">
            <!-- Left Primary Column -->
            <div class="main-content-col">
                @yield('content')
            </div>

            <!-- Right Sidebar Column -->
            <aside class="sidebar">
                @include('partials.sidebar')
            </aside>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <h4>Tentang AngananSoft</h4>
                    <p>{{ \App\Models\Setting::get('footer_about', 'AngananSoft adalah blog penyedia software multimedia, tools utilities, PC games, sistem operasi Windows, dan tutorial seputar komputer terlengkap dengan lisensi full version.') }}</p>
                    <p><i class="fa-solid fa-envelope" style="color:var(--primary-color)"></i> {{ \App\Models\Setting::get('contact_email', 'contact@anganansoft.com') }}</p>
                </div>
                <div class="footer-col">
                    <h4>Kategori Populer</h4>
                    <ul class="footer-links">
                        @foreach(\App\Models\Category::take(5)->get() as $fCat)
                        <li>
                            <a href="{{ route('blog.category', $fCat->slug) }}">
                                <i class="fa-solid fa-chevron-right" style="font-size:10px;"></i> {{ $fCat->name }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Entri Terbaru</h4>
                    <ul class="footer-links">
                        @if(isset($recentPosts))
                            @foreach($recentPosts->take(4) as $rPost)
                            <li>
                                <a href="{{ route('blog.show', $rPost->slug) }}">
                                    <i class="fa-solid fa-caret-right" style="color:var(--primary-color)"></i> {{ Str::limit($rPost->title, 36) }}
                                </a>
                            </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="container footer-bottom-inner">
                <div>
                    Copyright &copy; {{ date('Y') }} <strong style="color:#fff;">{{ \App\Models\Setting::get('site_name', 'AngananSoft') }}</strong>. All Rights Reserved.
                </div>
                <div>
                    Dibuat dengan <i class="fa-solid fa-heart" style="color:#e91e63;"></i> menggunakan <strong>Laravel</strong>.
                </div>
            </div>
        </div>
    </footer>

    <script>
        function copyPassword(text) {
            navigator.clipboard.writeText(text).then(function() {
                alert('Password "' + text + '" berhasil disalin ke clipboard!');
            });
        }
    </script>
    @stack('scripts')
</body>
</html>
