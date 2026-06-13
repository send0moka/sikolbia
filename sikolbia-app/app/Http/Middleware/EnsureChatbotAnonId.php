<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureChatbotAnonId
{
    public const COOKIE_NAME = 'chat_anon_id';
    public const REQUEST_ATTR = 'chatbot_anon_id';
    public const REQUEST_ATTR_FROM_COOKIE = 'chatbot_anon_id_from_cookie';

    private const TTL_MINUTES = 60 * 24 * 90; // 90 days

    /**
     * Ensure every request has a stable anonymous identifier for rate limiting.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $anonId = $this->getValidAnonIdFromCookie($request);
        $fromCookie = ($anonId !== null);
        $shouldSetCookie = false;

        if ($anonId === null) {
            $anonId = $this->generateAnonId();
            $shouldSetCookie = true;
        }

        $request->attributes->set(self::REQUEST_ATTR, $anonId);
        $request->attributes->set(self::REQUEST_ATTR_FROM_COOKIE, $fromCookie);

        /** @var Response $response */
        $response = $next($request);

        if ($shouldSetCookie) {
            $response->headers->setCookie(
                cookie(
                    self::COOKIE_NAME,
                    $this->signCookieValue($anonId),
                    self::TTL_MINUTES,
                    '/',
                    null,
                    $request->isSecure(),
                    true,
                    false,
                    'Lax'
                )
            );
        }

        return $response;
    }

    private function getValidAnonIdFromCookie(Request $request): ?string
    {
        $raw = $request->cookie(self::COOKIE_NAME);
        if (!is_string($raw) || $raw === '') {
            return null;
        }

        $parts = explode('.', $raw, 2);
        if (count($parts) !== 2) {
            return null;
        }

        [$id, $sig] = $parts;

        if (!is_string($id) || !preg_match('/^[a-f0-9]{32}$/i', $id)) {
            return null;
        }

        if (!is_string($sig) || !preg_match('/^[a-f0-9]{64}$/i', $sig)) {
            return null;
        }

        $expected = hash_hmac('sha256', $id, $this->hmacKey());
        if (!hash_equals($expected, $sig)) {
            return null;
        }

        return strtolower($id);
    }

    private function generateAnonId(): string
    {
        return bin2hex(random_bytes(16));
    }

    private function signCookieValue(string $anonId): string
    {
        $sig = hash_hmac('sha256', $anonId, $this->hmacKey());
        return $anonId . '.' . $sig;
    }

    private function hmacKey(): string
    {
        $key = (string) config('app.key', '');
        if ($key === '') {
            return 'missing-app-key';
        }

        if (str_starts_with($key, 'base64:')) {
            $decoded = base64_decode(substr($key, 7), true);
            if ($decoded !== false) {
                return $decoded;
            }
        }

        return $key;
    }
}
