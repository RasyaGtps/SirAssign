<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use Smalot\PdfParser\Parser;

class TextProcessingService
{
    /**
     * Clean and normalize text
     *
     * @param string $text
     * @return string
     */
    public function cleanText(string $text): string
    {
        // Remove extra whitespaces and normalize line breaks
        $text = preg_replace('/\s+/', ' ', $text);
        $text = preg_replace('/\r\n|\r|\n/', ' ', $text);
        
        // Remove special characters that might interfere with processing
        $text = preg_replace('/[^\p{L}\p{N}\p{P}\p{S}\s]/u', '', $text);
        
        // Normalize multiple spaces to single space
        $text = preg_replace('/\s{2,}/', ' ', $text);
        
        return trim($text);
    }

    /**
     * Split text into chunks with overlap
     *
     * @param string $text
     * @param int $chunkSize - Size in characters (approximately 250 tokens ≈ 1000 chars)
     * @param int $overlapSize - Overlap in characters (approximately 25 tokens ≈ 100 chars)
     * @return array
     */
    public function chunkText(string $text, int $chunkSize = 1000, int $overlapSize = 100): array
    {
        $text = $this->cleanText($text);
        $chunks = [];
        $textLength = strlen($text);
        
        if ($textLength <= $chunkSize) {
            return [[
                'index' => 0,
                'text' => $text,
                'start_position' => 0,
                'end_position' => $textLength,
            ]];
        }

        $start = 0;
        $chunkIndex = 0;

        while ($start < $textLength) {
            $end = min($start + $chunkSize, $textLength);
            
            // Try to break at sentence end to maintain context
            if ($end < $textLength) {
                $sentenceEnd = $this->findSentenceBreak($text, $end, $start);
                if ($sentenceEnd !== false) {
                    $end = $sentenceEnd;
                }
            }
            
            $chunk = substr($text, $start, $end - $start);
            $chunk = trim($chunk);
            
            if (!empty($chunk)) {
                $chunks[] = [
                    'index' => $chunkIndex,
                    'text' => $chunk,
                    'start_position' => $start,
                    'end_position' => $end,
                ];
                $chunkIndex++;
            }
            
            // Move start position with overlap
            $start = max($start + $chunkSize - $overlapSize, $end);
            
            // Prevent infinite loop
            if ($start >= $textLength) {
                break;
            }
        }

        return $chunks;
    }

    /**
     * Find optimal sentence break point
     *
     * @param string $text
     * @param int $preferredEnd
     * @param int $start
     * @return int|false
     */
    private function findSentenceBreak(string $text, int $preferredEnd, int $start): int|false
    {
        // Look for sentence endings within a reasonable range
        $searchStart = max($start, $preferredEnd - 200);
        $searchEnd = min(strlen($text), $preferredEnd + 100);
        
        $searchText = substr($text, $searchStart, $searchEnd - $searchStart);
        
        // Find sentence endings (., !, ?)
        $patterns = ['/\. /', '/\! /', '/\? /'];
        $bestPosition = false;
        $bestDistance = PHP_INT_MAX;
        
        foreach ($patterns as $pattern) {
            if (preg_match_all($pattern, $searchText, $matches, PREG_OFFSET_CAPTURE)) {
                foreach ($matches[0] as $match) {
                    $position = $searchStart + $match[1] + strlen($match[0]);
                    $distance = abs($position - $preferredEnd);
                    
                    if ($distance < $bestDistance && $position > $start) {
                        $bestPosition = $position;
                        $bestDistance = $distance;
                    }
                }
            }
        }
        
        return $bestPosition;
    }

    /**
     * Extract text from file content based on file type
     *
     * @param string $filePath
     * @param string $mimeType
     * @return string
     */
    public function extractTextFromFile(string $filePath, string $mimeType): string
    {
        try {
            switch ($mimeType) {
                case 'text/plain':
                    return file_get_contents($filePath);
                
                case 'application/pdf':
                    return $this->extractTextFromPDF($filePath);
                
                case 'application/msword':
                case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
                    return $this->extractTextFromWord($filePath);
                
                default:
                    // Try to read as plain text
                    $content = file_get_contents($filePath);
                    return mb_convert_encoding($content, 'UTF-8', 'auto');
            }
        } catch (Exception $e) {
            Log::error('Failed to extract text from file: ' . $e->getMessage());
            throw new Exception('Failed to extract text from file: ' . $e->getMessage());
        }
    }

    /**
     * Extract text from PDF using multiple parser attempts
     *
     * @param string $filePath
     * @return string
     */
    private function extractTextFromPDF(string $filePath): string
    {
        $text = '';
        
        // Method 1: Try smalot/pdfparser
        try {
            $parser = new Parser();
            $pdf = $parser->parseFile($filePath);
            $text = $pdf->getText();
            
            if (!empty(trim($text))) {
                Log::info('PDF extracted successfully with smalot/pdfparser', [
                    'file' => $filePath,
                    'length' => strlen($text)
                ]);
                return $this->cleanText($text);
            }
        } catch (Exception $e) {
            Log::warning('smalot/pdfparser failed: ' . $e->getMessage());
        }
        
        // Method 2: Try basic regex extraction
        try {
            $text = $this->basicPdfExtraction($filePath);
            if (!empty(trim($text))) {
                Log::info('PDF extracted with basic method', [
                    'file' => $filePath,
                    'length' => strlen($text)
                ]);
                return $text;
            }
        } catch (Exception $e) {
            Log::warning('Basic PDF extraction failed: ' . $e->getMessage());
        }
        
        // Method 3: Try reading raw content and filter
        try {
            $content = file_get_contents($filePath);
            $text = $this->extractReadableText($content);
            if (!empty(trim($text))) {
                Log::info('PDF extracted with raw content method', [
                    'file' => $filePath,
                    'length' => strlen($text)
                ]);
                return $this->cleanText($text);
            }
        } catch (Exception $e) {
            Log::warning('Raw content extraction failed: ' . $e->getMessage());
        }
        
        Log::error('All PDF extraction methods failed for: ' . $filePath);
        return '';
    }

    /**
     * Extract readable text from raw PDF content
     *
     * @param string $content
     * @return string
     */
    private function extractReadableText(string $content): string
    {
        // Remove binary data and keep only readable characters
        $text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F-\xFF]/', ' ', $content);
        
        // Extract text between common PDF text markers
        $patterns = [
            '/\((.*?)\)/s',  // Text in parentheses
            '/\[(.*?)\]/s',  // Text in brackets
            '/BT\s*(.*?)\s*ET/s', // Between BT and ET markers
        ];
        
        $extractedText = '';
        foreach ($patterns as $pattern) {
            if (preg_match_all($pattern, $content, $matches)) {
                foreach ($matches[1] as $match) {
                    $cleanMatch = trim($match);
                    if (strlen($cleanMatch) > 2 && ctype_print($cleanMatch)) {
                        $extractedText .= $cleanMatch . ' ';
                    }
                }
            }
        }
        
        // If nothing found, try to get any readable text
        if (empty(trim($extractedText))) {
            $lines = explode("\n", $text);
            foreach ($lines as $line) {
                $line = trim($line);
                if (strlen($line) > 3 && preg_match('/[a-zA-Z]/', $line)) {
                    $extractedText .= $line . ' ';
                }
            }
        }
        
        return trim($extractedText);
    }

    /**
     * Basic PDF text extraction fallback
     *
     * @param string $filePath
     * @return string
     */
    private function basicPdfExtraction(string $filePath): string
    {
        // Basic PDF text extraction (fallback)
        $content = file_get_contents($filePath);
        
        // Simple regex to extract text between BT and ET markers
        $text = '';
        if (preg_match_all('/BT(.*?)ET/s', $content, $matches)) {
            foreach ($matches[1] as $match) {
                if (preg_match_all('/\((.*?)\)/', $match, $textMatches)) {
                    foreach ($textMatches[1] as $textMatch) {
                        $text .= $textMatch . ' ';
                    }
                }
            }
        }
        
        // Fallback: try to extract any readable text
        if (empty(trim($text))) {
            $text = preg_replace('/[^\x20-\x7E\x0A\x0D]/', '', $content);
            $text = preg_replace('/\s+/', ' ', $text);
        }
        
        return $this->cleanText($text);
    }

    /**
     * Extract text from Word document (basic implementation)
     *
     * @param string $filePath
     * @return string
     */
    private function extractTextFromWord(string $filePath): string
    {
        // For DOCX files, you would typically use a library like PhpOffice/PhpWord
        // This is a basic implementation
        
        $content = file_get_contents($filePath);
        
        // For newer .docx files (ZIP-based)
        if (strpos($content, 'PK') === 0) {
            // This is a simplified extraction - in production use proper DOCX parser
            $text = strip_tags($content);
        } else {
            // For older .doc files
            $text = preg_replace('/[^\x20-\x7E\x0A\x0D]/', '', $content);
        }
        
        return $this->cleanText($text);
    }

    /**
     * Estimate token count (rough approximation: 1 token ≈ 4 characters)
     *
     * @param string $text
     * @return int
     */
    public function estimateTokenCount(string $text): int
    {
        return (int) ceil(strlen($text) / 4);
    }

    /**
     * Create metadata for chunk
     *
     * @param array $chunk
     * @param array $sourceMetadata
     * @return array
     */
    public function createChunkMetadata(array $chunk, array $sourceMetadata): array
    {
        return array_merge($sourceMetadata, [
            'chunk_index' => $chunk['index'],
            'chunk_start' => $chunk['start_position'],
            'chunk_end' => $chunk['end_position'],
            'chunk_length' => strlen($chunk['text']),
            'estimated_tokens' => $this->estimateTokenCount($chunk['text']),
            'created_at' => now()->toISOString(),
        ]);
    }
}