<?php

namespace App\Http\Swagger\Models;

/**
 * @OA\Schema(
 *     schema="Organization",
 *     type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="building_id", type="integer"),
 *     @OA\Property(
 *         property="phones",
 *         type="array",
 *         @OA\Items(type="string")
 *     ),
 *     @OA\Property(
 *         property="activities",
 *         type="array",
 *         @OA\Items(type="string")
 *     )
 * )
 */
class Organization {}
