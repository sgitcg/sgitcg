<?php
/**
 * Profile README Generator Test Suite
 * 
 * This script tests the profile README generator to ensure
 * it works correctly before deployment.
 * 
 * @author Sebastian Gorr
 * @version 1.0.0
 */

class ProfileReadmeGeneratorTest
{
    private array $testResults = [];
    private string $testFile = 'PROFILE_README_TEST.md';
    
    public function runAllTests(): bool
    {
        echo "🧪 Starting Profile README Generator Test Suite\n";
        echo "=============================================\n\n";
        
        $tests = [
            'testGeneratorExists',
            'testGeneratorSyntax',
            'testGeneratorExecution',
            'testOutputFileCreated',
            'testContentValidation',
            'testRequiredSections',
            'testDynamicContent',
            'testFileSize',
            'testMarkdownSyntax',
            'testCleanup'
        ];
        
        $passed = 0;
        $total = count($tests);
        
        foreach ($tests as $test) {
            echo "🔍 Running: $test\n";
            
            try {
                if ($this->$test()) {
                    echo "✅ PASSED: $test\n";
                    $this->testResults[$test] = 'PASSED';
                    $passed++;
                } else {
                    echo "❌ FAILED: $test\n";
                    $this->testResults[$test] = 'FAILED';
                }
            } catch (Exception $e) {
                echo "💥 ERROR: $test - " . $e->getMessage() . "\n";
                $this->testResults[$test] = 'ERROR: ' . $e->getMessage();
            }
            
            echo "\n";
        }
        
        $this->printSummary($passed, $total);
        
        return $passed === $total;
    }
    
    private function testGeneratorExists(): bool
    {
        return file_exists('tools/profile_readme_generator.php');
    }
    
    private function testGeneratorSyntax(): bool
    {
        $output = [];
        $returnCode = 0;
        
        exec('php -l tools/profile_readme_generator.php 2>&1', $output, $returnCode);
        
        return $returnCode === 0;
    }
    
    private function testGeneratorExecution(): bool
    {
        $output = [];
        $returnCode = 0;
        
        // Capture output and return code
        exec('php tools/profile_readme_generator.php 2>&1', $output, $returnCode);
        
        return $returnCode === 0;
    }
    
    private function testOutputFileCreated(): bool
    {
        return file_exists('PROFILE_README_TEMP.md');
    }
    
    private function testContentValidation(): bool
    {
        if (!file_exists('PROFILE_README_TEMP.md')) {
            return false;
        }
        
        $content = file_get_contents('PROFILE_README_TEMP.md');
        
        // Basic content checks
        $checks = [
            'Sebastian Gorr',
            '🔭 Check out what I\'m currently working on',
            'WorkSafteyCore',
            '🛠️ Tech Stack',
            'Private',
            'Enterprise',
            '📫 How to reach me'
        ];
        
        foreach ($checks as $check) {
            if (strpos($content, $check) === false) {
                echo "   Missing: $check\n";
                return false;
            }
        }
        
        return true;
    }
    
    private function testRequiredSections(): bool
    {
        if (!file_exists('PROFILE_README_TEMP.md')) {
            return false;
        }
        
        $content = file_get_contents('PROFILE_README_TEMP.md');
        
        $requiredSections = [
            '## 🔭 Check out what I\'m currently working on',
            '## ⚡ Private repositories I\'m actively developing',
            '## 🎯 Latest releases from my private projects',
            '## ⭐ Recent Stars',
            '## 📌 Featured Private Projects',
            '## 🏆 Achievements & Highlights',
            '## 📫 How to reach me',
            '## 🛠️ Tech Stack & Tools',
            '## 🔒 About My Private Repositories'
        ];
        
        foreach ($requiredSections as $section) {
            if (strpos($content, $section) === false) {
                echo "   Missing section: $section\n";
                return false;
            }
        }
        
        return true;
    }
    
    private function testDynamicContent(): bool
    {
        if (!file_exists('PROFILE_README_TEMP.md')) {
            return false;
        }
        
        $content = file_get_contents('PROFILE_README_TEMP.md');
        
        // Check for dynamic timestamps
        $currentYear = date('Y');
        $currentDate = date('F j, Y');
        
        if (strpos($content, $currentYear) === false) {
            echo "   Missing current year\n";
            return false;
        }
        
        if (strpos($content, $currentDate) === false) {
            echo "   Missing current date\n";
            return false;
        }
        
        // Check for version numbers
        if (!preg_match('/v\d+\.\d+\.\d+/', $content)) {
            echo "   Missing version numbers\n";
            return false;
        }
        
        return true;
    }
    
    private function testFileSize(): bool
    {
        if (!file_exists('PROFILE_README_TEMP.md')) {
            return false;
        }
        
        $size = filesize('PROFILE_README_TEMP.md');
        $lines = count(file('PROFILE_README_TEMP.md'));
        
        // Minimum requirements
        if ($size < 5000) { // At least 5KB
            echo "   File too small: $size bytes\n";
            return false;
        }
        
        if ($lines < 100) { // At least 100 lines
            echo "   Too few lines: $lines\n";
            return false;
        }
        
        echo "   File size: $size bytes, Lines: $lines\n";
        return true;
    }
    
    private function testMarkdownSyntax(): bool
    {
        if (!file_exists('PROFILE_README_TEMP.md')) {
            return false;
        }
        
        $content = file_get_contents('PROFILE_README_TEMP.md');
        
        // Basic markdown syntax checks
        $patterns = [
            '/^# .+$/m',              // Headers
            '/!\[.+\]\(.+\)/',        // Images
            '/\[.+\]\(.+\)/',         // Links
            '/\*\*.+\*\*/',           // Bold text
            '/`.+`/',                 // Code
            '/^- .+$/m'               // Lists
        ];
        
        foreach ($patterns as $pattern) {
            if (!preg_match($pattern, $content)) {
                echo "   Missing markdown pattern: $pattern\n";
                return false;
            }
        }
        
        return true;
    }
    
    private function testCleanup(): bool
    {
        $filesToClean = ['PROFILE_README_TEMP.md', $this->testFile];
        $cleaned = true;
        
        foreach ($filesToClean as $file) {
            if (file_exists($file)) {
                if (!unlink($file)) {
                    echo "   Failed to clean: $file\n";
                    $cleaned = false;
                }
            }
        }
        
        return $cleaned;
    }
    
    private function printSummary(int $passed, int $total): void
    {
        echo "📊 Test Summary\n";
        echo "==============\n";
        echo "✅ Passed: $passed\n";
        echo "❌ Failed: " . ($total - $passed) . "\n";
        echo "📊 Total:  $total\n";
        echo "📈 Success Rate: " . round(($passed / $total) * 100, 1) . "%\n\n";
        
        if ($passed === $total) {
            echo "🎉 All tests passed! Generator is ready for deployment.\n";
        } else {
            echo "⚠️  Some tests failed. Please review before deployment.\n";
            echo "\nDetailed Results:\n";
            foreach ($this->testResults as $test => $result) {
                $icon = strpos($result, 'PASSED') !== false ? '✅' : '❌';
                echo "$icon $test: $result\n";
            }
        }
    }
}

// Main execution
try {
    $tester = new ProfileReadmeGeneratorTest();
    
    if ($tester->runAllTests()) {
        echo "\n🚀 All tests passed! Ready to deploy.\n";
        exit(0);
    } else {
        echo "\n⚠️ Tests failed! Please fix issues before deployment.\n";
        exit(1);
    }
    
} catch (Exception $e) {
    echo "💥 Test suite error: " . $e->getMessage() . "\n";
    exit(1);
}
