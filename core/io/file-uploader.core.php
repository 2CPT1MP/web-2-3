<?php

class UploadedFileInfo {
    private bool $isSuccessful;
    private string | null $serverFilepath;

    public function __construct(bool $isSuccessful, string $serverFilepath = null) {
        $this->isSuccessful = $isSuccessful;
        $this->serverFilepath = $serverFilepath;
    }

    public function isSuccessful(): bool { return $this->isSuccessful; }
    public function getServerFilepath(): string { return $this->serverFilepath; }
}

class FileUploader {

    public static function uploadFile(string $clientFilename, string $serverFilepath, $validatorFunction = null): UploadedFileInfo {
        $fileNotAttached = !isset($_FILES[$clientFilename]) || !isset($_FILES[$clientFilename]["tmp_name"]);
        if ($fileNotAttached)
            return new UploadedFileInfo(false);

        $file = $_FILES[$clientFilename]["tmp_name"];
        if ($validatorFunction && !$validatorFunction($file))
            return new UploadedFileInfo(false);

        if (!copy($file, $serverFilepath))
            return new UploadedFileInfo(false);
        return new UploadedFileInfo(true, $serverFilepath);
    }
}