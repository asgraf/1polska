<?php
namespace App\Shell;

use Cake\Console\Shell;

/**
 * ClearThumbs shell command.
 *
 * @property \Filerepo\Model\Table\FileobjectsTable $Fileobjects
 */
class ClearThumbsShell extends Shell
{
    public function main()
    {
        $this->loadModel('Filerepo.Fileobjects');
	    $this->Fileobjects->cleanupUnusedFiles('Representatives');
	    $this->out('Nieużywane zdjęcia reprezentantów przeczyszczone');
	    $this->Fileobjects->cleanupUnusedFiles('Filerepo.Fileobjects');
	    $this->out('Nieużywane miniaturki przeczyszczone');
    }
}
