<?php

namespace App\Service\Validation;

use App\Exception\Validation\InvalidJsonException;
use App\Exception\Validation\MissingRequiredFieldException;
use InvalidArgumentException;
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
     * @param string[] $expectedFields List of required fields
     * @return array Decoded JSON as an associative array
     * @throws InvalidArgumentException If JSON is invalid or required fields are missing
     */
    public function decodeJson(Request $request, array $expectedFields = []): array
    {
        $data = json_decode($request->getContent(), true);

        // Check for JSON decoding errors
        if ($data === null || json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidJsonException();
        }

        // Validate required fields
        foreach ($expectedFields as $field) {
            if (!isset($data[$field])) {
                throw new MissingRequiredFieldException($field);
            }
        }

        return $data;
    }
}
