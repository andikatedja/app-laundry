<?php

namespace App\Models\Concerns;

use App\Exceptions\ProfilePictureException;
use App\Jobs\DeleteProfilePicture;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait ProfilePicture
{
    /**
     * The "booted" method of the trait.
     *
     * @return void
     */
    public static function bootProfilePicture(): void
    {
        static::saving(
            /**
             * @param \Illuminate\Database\Eloquent\Model<\App\Models\Contracts\UploadedFilesInterface> $model
             */
            function ($model) {
                // Check if there is any changes in image attribute
                // Such as storing and updating to table
                if ($model->isDirty(attributes: $model->fileColumn())) {
                    // When updating, usually model has previous file
                    // Check if model has previous file
                    // If has previous file, then delete
                    if ($model->hasPreviousFile() && !$model->isDefaultFileName()) {
                        $model->deletePreviousFile();
                    }

                    // Get the attribute value
                    $file = $model->getAttribute(key: $model->fileColumn());

                    // Check if file is really a file
                    if ($file instanceof UploadedFile) {
                        $model->saveFile(file: $file);
                    }
                }
            }
        );

        static::deleting(
            /**
             * @param \Illuminate\Database\Eloquent\Model<\App\Models\Contracts\UploadedFilesInterface> $model
             */
            function ($model) {
                $model->deletePreviousFile();
            }
        );
    }

    /**
     * Return column name for storing file name
     *
     * @return string
     */
    public function fileColumn(): string
    {
        return 'file';
    }

    /**
     * Get hashed name from uploaded file
     *
     * @param UploadedFile $file
     * @return string
     */
    public function getUploadedFilename(UploadedFile $file): string
    {
        return $file->hashName();
    }

    /**
     * File path for storing and getting uploaded file
     *
     * @return string
     */
    public function getFilePath(): string
    {
        return 'files';
    }

    /**
     * Get storage name
     *
     * @return string
     */
    public function getStorageName(): string
    {
        return config('filesystems.default', 'public');
    }

    /**
     * Get storage disk filesystem
     *
     * @return Filesystem|FilesystemAdapter
     */
    public function getFileStorage(): Filesystem|FilesystemAdapter
    {
        return Storage::disk($this->getStorageName());
    }

    /**
     * Get error message for uploaded file
     *
     * @return string
     */
    public function getFailedMessage(): string
    {
        return 'Failed to process uploaded file';
    }

    /**
     * Check if current model attribute has file
     *
     * @return boolean
     */
    public function hasFile(): bool
    {
        return !blank($this->getAttribute($this->fileColumn()));
    }

    /**
     * Get the full file path
     *
     * @return string
     */
    public function getFullFilePath(): string
    {
        return $this->getFilePath() . '/' . $this->getAttribute($this->fileColumn());
    }

    /**
     * Get previous file path
     *
     * @return string
     */
    public function getPreviousFilePath(): string
    {
        return $this->getFilePath() . '/' . $this->getRawOriginal($this->fileColumn());
    }

    /**
     * The default file name
     *
     * @return string
     */
    public function getDefaultFileName(): string
    {
        return 'default.jpg';
    }

    /**
     * Check if model has default file name
     *
     * @return boolean
     */
    public function isDefaultFileName(): bool
    {
        return $this->getRawOriginal($this->fileColumn()) === $this->getDefaultFileName();
    }

    /**
     * Save the file
     *
     * @param UploadedFile $file
     *
     * @return void
     *
     * @throws \App\Exceptions\ProfilePictureException
     */
    public function saveFile(UploadedFile $file): void
    {
        // Set file name with hash
        $filename = $this->getUploadedFilename(file: $file);

        // Move file to storage
        $uploaded = $file->storeAs(
            path: $this->getFilePath(),
            name: $filename,
            options: $this->getStorageName()
        );

        if (!$uploaded) {
            throw new ProfilePictureException($this->getFailedMessage(), 1);
        }

        // Set the file attribute value to filename
        $this->setAttribute(
            key: $this->fileColumn(),
            value: $filename
        );
    }


    /**
     * Mutator for getting file asset path
     *
     * @return string|null
     */
    public function getFileAsset(): ?string
    {
        if (!$this->hasFile()) {
            return null;
        }

        return $this->getFileStorage()->url($this->getFullFilePath());
    }

    /**
     * Check if previous original value is a file
     *
     * @return boolean
     */
    public function hasPreviousFile(): bool
    {
        return !blank($this->getRawOriginal($this->fileColumn()));
    }

    /**
     * Delete previous file
     *
     * @return void
     */
    public function deletePreviousFile(): void
    {
        if (!$this->hasPreviousFile()) {
            return;
        }

        if ($this->isDefaultFileName()) {
            return;
        }

        DeleteProfilePicture::dispatch(
            filePath: $this->getPreviousFilePath(),
            disk: $this->getStorageName()
        )->afterCommit();
    }
}
