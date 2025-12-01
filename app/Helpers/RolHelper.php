<?php

use Illuminate\Support\Facades\Auth;

/**
 * Verifica si el usuario tiene un permiso específico
 */
function userHasPermission(string $permiso): bool
{
    if (!Auth::check()) return false;

    $rol = Auth::user()->rol ?? null;

    if (!$rol || !$rol->permisos) return false;

    $permisos = json_decode($rol->permisos, true) ?? [];

    return in_array($permiso, $permisos);
}

/**
 * Verifica si el usuario tiene un rol específico
 */
function userHasRole(string $nombreRol): bool
{
    if (!Auth::check()) return false;

    return strtolower(Auth::user()->rol->nombre ?? '') === strtolower($nombreRol);
}
