<?php

namespace App\Validator;

use App\Exception\Validation\InvalidJsonException;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RequestValidator
 *
 * A simple reusable service to decode JSON requests and validate required fields.
 *
 * Usage:
 *   $requestValidator = new RequestValidator();
 *   try {
 *       $data = $requestValidator->decodeJson($request, ['oldRefreshToken']);
 *       // $data['oldRefreshToken'] is now guaranteed to exist
 *   } catch (\InvalidArgumentException $e) {
 *       return $this->json(['error' => $e->getMessage()], 400);
 *   }
 */
class RequestValidator
{
    /**
     * Decode JSON from the request and validate required fields.
     *
     * @param Request $request The incoming HTTP request
     * @return array Decoded JSON as an associative array
     * @throws InvalidJsonException If the JSON is invalid
     */
    public function decodeJson(Request $request): array
    {
        $data = json_decode($request->getContent(), true);

        // Check for JSON decoding errors
        if ($data === null || json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidJsonException();
        }

        return $data;
    }
}
