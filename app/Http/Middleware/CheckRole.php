<?php 
namespace App\Http\Middleware; 
use Closure; 
use Illuminate\Http\Request; 
class CheckRole { 
    public function handle(Request $request, Closure $next) { 
        $user = $request->user(); 
        if (!$user || $user->role !== 'admin') { 
            return response()->json(['message' => 'Forbidden'], 403); 
        } 
        abort(403, 'Forbidden');
        return $next($request); 
    }
 }
 ?>