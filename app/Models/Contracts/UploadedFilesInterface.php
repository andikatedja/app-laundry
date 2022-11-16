<?php

namespace App\Models\Contracts;

use Illuminate\Http\UploadedFile;

interface UploadedFilesInterface
{
    /**
     * Return column name for storing file name
     *
     * @return string
     */
    public function fileColumn(): string;

    /**
     * Check if previous original value is a file
     *
     * @return boolean
     */
    public function hasPreviousFile(): bool;

    /**
     * Delete previous file
     *
     * @return void
     */
    public function deletePreviousFile(): void;

    /**
     * Save the file
     *
     * @param  \Illuminate\Http\UploadedFile $file
     *
     * @return void
     *
     * @throws \App\Exceptions\ProfilePictureException
     */
    public function saveFile(UploadedFile $file): void;
}
