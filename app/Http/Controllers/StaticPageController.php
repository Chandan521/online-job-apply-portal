<?php

namespace App\Http\Controllers;

use App\Models\StaticPage;
use Illuminate\Http\Request;

class StaticPageController extends Controller
{
    public function publicIndex()
    {
        $pages = StaticPage::where('is_visible', true)->get();
        return view('pages.index', compact('pages'));
    }
    public function show($slug)
    {
        $page = StaticPage::where('slug', $slug)->where('is_visible', true)->first();

        if (!$page) {
            // Optional fallback
            $fallbacks = [
                'help' => '
        <h2>Need Help?</h2>
        <p>We\'re here to assist you. Below are some common topics:</p>
        <ul>
            <li><strong>Account Issues:</strong> Forgot your password or can\'t log in? <a href="">Reset it here</a>.</li>
            <li><strong>Job Applications:</strong> Having trouble applying? Make sure your profile is complete.</li>
            <li><strong>Technical Support:</strong> Facing bugs or errors? Contact us via the <a href="">Contact Page</a>.</li>
        </ul>
        <p>If you still need help, don\'t hesitate to reach out to our support team. We typically respond within 24 hours.</p>
    ',

                'privacy' => '
        <h2>Privacy Policy</h2>
        <p>Your privacy matters to us. Here\'s how we protect your data:</p>
        <ul>
            <li>🔒 <strong>Data Security:</strong> All your information is encrypted and securely stored.</li>
            <li>🙅‍♂️ <strong>No Selling:</strong> We never sell your personal information to third parties.</li>
            <li>🔄 <strong>Control:</strong> You can view, edit, or delete your data anytime from your profile settings.</li>
        </ul>
        <p>By using our platform, you agree to our data handling practices. For full details, read the complete privacy policy or contact us directly for clarifications.</p>
    '
            ];


            if (array_key_exists($slug, $fallbacks)) {
                return view('pages.show', [
                    'page' => (object)[
                        'title' => ucfirst($slug),
                        'content' => $fallbacks[$slug]
                    ]
                ]);
            }

            // If not in fallback, show 404
            abort(404);
        }

        return view('pages.show', compact('page'));
    }
}
