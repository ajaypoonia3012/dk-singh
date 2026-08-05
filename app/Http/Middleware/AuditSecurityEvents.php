<?php

namespace App\Http\Middleware;

use App\Models\SecurityAuditLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class AuditSecurityEvents
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $response = $next($request);
            $this->record($request, $response->getStatusCode());

            return $response;
        } catch (Throwable $exception) {
            $this->record($request, 500, $exception::class);

            throw $exception;
        }
    }

    private function record(Request $request, int $status, ?string $exception = null): void
    {
        if ($request->isMethodSafe() || ! Schema::hasTable('security_audit_logs')) {
            return;
        }

        try {
            SecurityAuditLog::query()->create([
                'user_id' => $request->user()?->getAuthIdentifier(),
                'event' => $request->route()?->getName() ?? 'http.request',
                'method' => $request->method(),
                'path' => '/'.ltrim($request->path(), '/'),
                'status_code' => $status,
                'ip_address' => $request->ip(),
                'user_agent' => mb_substr((string) $request->userAgent(), 0, 512),
                'context' => array_filter([
                    'route_action' => $request->route()?->getActionName(),
                    'exception' => $exception,
                ]),
            ]);
        } catch (Throwable $exception) {
            Log::warning('Security audit event could not be persisted.', [
                'exception' => $exception::class,
                'event' => $request->route()?->getName(),
            ]);
        }
    }
}
