@extends('admin.layout.app')

@section('title', 'Admin - Settings')


@section('content')
    <div class="settings-wrapper">
        <div class="page-header mb-4">
            <h1 class="page-title">Site Settings</h1>
            <p class="page-subtitle">Manage your site configuration and preferences.</p>
        </div>

        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="settings-form">
            @csrf
            @method('POST')

            <div class="settings-container">
                <div class="settings-sidebar">
                    <ul class="nav flex-column settings-nav" id="settings-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="general-tab" data-bs-toggle="tab" href="#general" role="tab"
                                aria-controls="general" aria-selected="true">
                                <i class="bi bi-gear-wide-connected"></i> General
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="branding-tab" data-bs-toggle="tab" href="#branding" role="tab"
                                aria-controls="branding" aria-selected="false">
                                <i class="bi bi-gem"></i> Branding
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="app-config-tab" data-bs-toggle="tab" href="#app-config" role="tab"
                                aria-controls="app-config" aria-selected="false">
                                <i class="bi bi-box-seam"></i> Application
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="mail-tab" data-bs-toggle="tab" href="#mail" role="tab"
                                aria-controls="mail" aria-selected="false">
                                <i class="bi bi-envelope"></i> Mail Server
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="database-tab" data-bs-toggle="tab" href="#database" role="tab"
                                aria-controls="database" aria-selected="false">
                                <i class="bi bi-database"></i> Database
                            </a>
                        </li>

                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="pusher-tab" data-bs-toggle="tab" href="#pusher" role="tab"
                                aria-controls="pusher" aria-selected="false">
                                <i class="bi bi-cloud-check"></i> Pusher
                            </a>
                        </li>


                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="advanced-tab" data-bs-toggle="tab" href="#advanced" role="tab"
                                aria-controls="advanced" aria-selected="false">
                                <i class="bi bi-tools"></i> Advanced
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="settings-content card">
                    <div class="card-body tab-content" id="settings-tab-content">
                        {{-- General Settings --}}
                        <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                            <h5 class="card-title mb-4">General Settings</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="site_name" class="form-label">Site Name</label>
                                    <input type="text" name="site_name" id="site_name" class="form-control"
                                        value="{{ old('site_name', setting('site_name', config('app.name'))) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="site_email" class="form-label">Contact Email</label>
                                    <input type="email" name="site_email" id="site_email" class="form-control"
                                        value="{{ old('site_email', setting('site_email')) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="maintenance_mode" class="form-label">Maintenance Mode</label>
                                    <select name="maintenance_mode" class="form-select">
                                        <option value="off"
                                            {{ setting('maintenance_mode') == 'off' ? 'selected' : '' }}>
                                            Off</option>
                                        <option value="on" {{ setting('maintenance_mode') == 'on' ? 'selected' : '' }}>
                                            On</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Branding Settings --}}
                        <div class="tab-pane fade" id="branding" role="tabpanel" aria-labelledby="branding-tab">
                            <h5 class="card-title mb-4">Branding</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="site_logo" class="form-label">Site Logo</label>
                                    <input type="file" name="site_logo" class="form-control">
                                    @if (setting('site_logo'))
                                        <div class="mt-3">
                                            <img src="{{ asset('storage/' . setting('site_logo')) }}" height="50"
                                                alt="Logo" class="img-thumbnail">
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="site_favicon" class="form-label">Favicon</label>
                                    <input type="file" name="site_favicon" class="form-control">
                                    @if (setting('site_favicon'))
                                        <div class="mt-3">
                                            <img src="{{ asset('storage/' . setting('site_favicon')) }}" height="32"
                                                alt="Favicon" class="img-thumbnail">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- App Config --}}
                        <div class="tab-pane fade" id="app-config" role="tabpanel" aria-labelledby="app-config-tab">
                            <h5 class="card-title mb-4">Application Configuration</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="app_url" class="form-label">App URL</label>
                                    <input type="text" class="form-control" name="app_url"
                                        value="{{ setting('app_url', config('app.url')) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="app_debug" class="form-label">App Debug</label>
                                    <select name="app_debug" class="form-select">
                                        <option value="true" {{ setting('app_debug') == 'true' ? 'selected' : '' }}>True
                                        </option>
                                        <option value="false" {{ setting('app_debug') == 'false' ? 'selected' : '' }}>
                                            False</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="mail_enabled" class="form-label">Mail Enabled</label>
                                    <select name="mail_enabled" class="form-select">
                                        <option value="1" {{ setting('mail_enabled') == '1' ? 'selected' : '' }}>
                                            Enabled</option>
                                        <option value="0" {{ setting('mail_enabled') == '0' ? 'selected' : '' }}>
                                            Disabled</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Mail Config --}}
                        <div class="tab-pane fade" id="mail" role="tabpanel" aria-labelledby="mail-tab">
                            <h5 class="card-title mb-4">Mail Server Configuration</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Mail Mailer</label>
                                    <select name="mail_mailer" class="form-select">
                                        <option value="smtp" {{ setting('mail_mailer') == 'smtp' ? 'selected' : '' }}>
                                            SMTP</option>
                                        <option value="sendmail"
                                            {{ setting('mail_mailer') == 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                                        <option value="mailgun"
                                            {{ setting('mail_mailer') == 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                                        <option value="ses" {{ setting('mail_mailer') == 'ses' ? 'selected' : '' }}>
                                            SES</option>
                                        <option value="postmark"
                                            {{ setting('mail_mailer') == 'postmark' ? 'selected' : '' }}>Postmark</option>
                                        <option value="log" {{ setting('mail_mailer') == 'log' ? 'selected' : '' }}>
                                            Log</option>
                                        <option value="array" {{ setting('mail_mailer') == 'array' ? 'selected' : '' }}>
                                            Array</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Mail Host</label>
                                    <input type="text" class="form-control" name="mail_host"
                                        value="{{ setting('mail_host', 'smtp.gmail.com') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Mail Port</label>
                                    <input type="text" class="form-control" name="mail_port"
                                        value="{{ setting('mail_port', '587') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Username</label>
                                    <input type="text" class="form-control" name="mail_username"
                                        value="{{ setting('mail_username') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" class="form-control" name="mail_password"
                                        value="{{ setting('mail_password') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">From Address</label>
                                    <input type="email" class="form-control" name="mail_from_address"
                                        value="{{ setting('mail_from_address') }}">
                                </div>
                            </div>
                        </div>
                        {{-- Pusher Config --}}
                        <div class="tab-pane fade" id="pusher" role="tabpanel" aria-labelledby="pusher-tab">
                            <h5 class="card-title mb-4">Pusher Configuration</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">App ID</label>
                                    <input type="text" class="form-control" name="pusher_app_id"
                                        value="{{ setting('pusher_app_id') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">App Key</label>
                                    <input type="text" class="form-control" name="pusher_app_key"
                                        value="{{ setting('pusher_app_key') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">App Secret</label>
                                    <input type="text" class="form-control" name="pusher_app_secret"
                                        value="{{ setting('pusher_app_secret') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">App Cluster</label>
                                    <input type="text" class="form-control" name="pusher_app_cluster"
                                        value="{{ setting('pusher_app_cluster', 'ap2') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Pusher Scheme</label>
                                    <input type="text" class="form-control" name="pusher_scheme"
                                        value="{{ setting('pusher_scheme', 'https') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Pusher Host</label>
                                    <input type="text" class="form-control" name="pusher_host"
                                        value="{{ setting('pusher_host') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Pusher Port</label>
                                    <input type="text" class="form-control" name="pusher_port"
                                        value="{{ setting('pusher_port', '443') }}">
                                </div>
                            </div>
                        </div>


                        {{-- DB Config --}}
                        <div class="tab-pane fade" id="database" role="tabpanel" aria-labelledby="database-tab">
                            <h5 class="card-title mb-4">Database Configuration</h5>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Connection</label>
                                    <select name="db_connection" class="form-select">
                                        <option value="mysql"
                                            {{ setting('db_connection') == 'mysql' ? 'selected' : '' }}>MySQL</option>
                                        <option value="sqlite"
                                            {{ setting('db_connection') == 'sqlite' ? 'selected' : '' }}>SQLite</option>
                                        <option value="pgsql"
                                            {{ setting('db_connection') == 'pgsql' ? 'selected' : '' }}>PostgreSQL
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Host</label>
                                    <input type="text" class="form-control" name="db_host"
                                        value="{{ setting('db_host', '127.0.0.1') }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Port</label>
                                    <input type="text" class="form-control" name="db_port"
                                        value="{{ setting('db_port', '3306') }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Database Name</label>
                                    <input type="text" class="form-control" name="db_database"
                                        value="{{ setting('db_database') }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Username</label>
                                    <input type="text" class="form-control" name="db_username"
                                        value="{{ setting('db_username', 'root') }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" class="form-control" name="db_password"
                                        value="{{ setting('db_password') }}">
                                </div>
                            </div>
                        </div>

                        {{-- Advanced --}}
                        <div class="tab-pane fade" id="advanced" role="tabpanel" aria-labelledby="advanced-tab">
                            <h5 class="card-title mb-4">Advanced Actions</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card bg-light border-warning">
                                        <div class="card-body">
                                            <h6 class="card-title text-warning">Run Migrations</h6>
                                            <p class="card-text text-muted small">This will run all pending database
                                                migrations. Make sure you have a backup.</p>
                                            <button type="submit" name="action" value="migrate"
                                                class="btn btn-warning">Run Migrations</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card bg-light border-danger">
                                        <div class="card-body">
                                            <h6 class="card-title text-danger">Run Seeding</h6>
                                            <p class="card-text text-muted small">This will seed the database with initial
                                                data. Use with caution on a production server.</p>
                                            <button type="submit" name="action" value="seed"
                                                class="btn btn-danger">Run Seeder</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-save"></i> Save All Settings
                </button>
            </div>
        </form>
    </div>
@endsection

@push('dashboard-styles')
    <style>
        .settings-wrapper {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .settings-form {
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .settings-container {
            display: grid;
            grid-template-columns: 240px 1fr;
            gap: 2rem;
            flex-grow: 1;
        }

        .settings-sidebar {
            background-color: --dark-color;
            border-radius: 1rem;
            padding: 1rem;
            box-shadow: var(--card-shadow);
            border: 1px solid var(--border-color);
            align-self: flex-start;
        }

        .settings-nav .nav-link {
            color: var(--text-secondary);
            font-weight: 500;
            padding: 0.875rem 1rem;
            border-radius: 0.5rem;
            margin-bottom: 0.25rem;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .settings-nav .nav-link i {
            font-size: 1.125rem;
            width: 20px;
            text-align: center;
        }

        .settings-nav .nav-link:hover {
            background-color: var(--light-color);
            color: var(--primary-color);
        }

        .settings-nav .nav-link.active {
            background-color: var(--primary-color);
            color: white;
            box-shadow: 0 4px 8px rgba(37, 99, 235, 0.2);
        }

        .settings-content {
            padding: 1.5rem;
        }

        .tab-pane {
            animation: fadeIn 0.3s ease-out;
        }

        .form-label {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .form-control,
        .form-select {
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
        }

        .img-thumbnail {
            padding: 0.5rem;
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            background-color: #fafbfc;
        }

        .form-actions {
            background-color: --dark-color;
            padding: 1.5rem;
            border-top: 1px solid var(--border-color);
            text-align: right;
            margin-top: 2rem;
            border-radius: 1rem;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
        }

        @media (max-width: 992px) {
            .settings-container {
                grid-template-columns: 1fr;
            }

            .settings-sidebar {
                margin-bottom: 1.5rem;
            }

            .settings-nav {
                flex-direction: row !important;
                flex-wrap: wrap;
            }
        }
    </style>
@endpush
