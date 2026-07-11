<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class ProgramTenantRouter
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        
        // Define base domains to exclude from subdomain routing
        $baseDomains = ['localhost', '127.0.0.1', 'domain.com', 'profil.instituthijauindonesia.or.id'];
        
        $program = null;

        // Verify the programs table exists to prevent early setup and testing crashes
        if (Schema::hasTable('programs')) {
            // Try custom domain first
            $program = Program::where('custom_domain', $host)
                ->where('status', 'active')
                ->first();

            // Try subdomain if not matching base domains
            if (!$program) {
                foreach ($baseDomains as $baseDomain) {
                    if (str_ends_with($host, '.' . $baseDomain)) {
                        $subdomain = str_replace('.' . $baseDomain, '', $host);
                        // Avoid routing panel/dashboard subdomains as programs
                        if ($subdomain !== 'dashboard' && $subdomain !== 'admin') {
                            $program = Program::where('subdomain', $subdomain)
                                ->where('status', 'active')
                                ->first();
                            if ($program) {
                                break;
                            }
                        }
                    }
                }
            }

            // Try path fallback if route has a slug parameter (e.g. /p/{program_slug})
            if (!$program) {
                $route = $request->route();
                if ($route && $route->hasParameter('program_slug')) {
                    $slug = $route->parameter('program_slug');
                    $program = Program::where('slug', $slug)
                        ->where('status', 'active')
                        ->first();
                }
            }

            // Abort 404 if accessed as sub-site context but no program is resolved
            if (!$program) {
                $isSubsiteDomain = true;
                foreach ($baseDomains as $baseDomain) {
                    if ($host === $baseDomain || $host === 'www.' . $baseDomain) {
                        $isSubsiteDomain = false;
                    }
                }
                
                $route = $request->route();
                $isSubsiteRoute = ($route && $route->hasParameter('program_slug'));

                if ($isSubsiteDomain || $isSubsiteRoute) {
                    abort(404, 'Program profile not found or inactive.');
                }
            }
        }

        if ($program) {
            // Share the resolved program with all Blade views and register it as a singleton
            view()->share('currentProgram', $program);
            app()->instance(Program::class, $program);
        }

        return $next($request);
    }
}
