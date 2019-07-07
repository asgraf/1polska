<?php
namespace App\Command;

use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;

/**
 * Newsletter command.
 *
 * @property \App\Model\Table\NewslettersTable $Newsletters
 */
class NewsletterCommand extends Command
{
	public function initialize()
	{
		parent::initialize();
		$this->loadModel('Newsletters');
	}

    /**
     * Hook method for defining this command's option parser.
     *
     * @see https://book.cakephp.org/3.0/en/console-and-shells/commands.html#defining-arguments-and-options
     *
     * @param \Cake\Console\ConsoleOptionParser $parser The parser to be defined
     * @return \Cake\Console\ConsoleOptionParser The built parser.
     */
    public function buildOptionParser(ConsoleOptionParser $parser)
    {
        $parser = parent::buildOptionParser($parser);

        return $parser;
    }

    /**
     * Implement this method with your command's logic.
     *
     * @param \Cake\Console\Arguments $args The command arguments.
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return null|int The exit code or null for success
     */
    public function execute(Arguments $args, ConsoleIo $io)
    {
	    /**	@var \Cake\ORM\Query | \App\Model\Entity\Newsletter[] $newsletters */
		$newsletters = $this->Newsletters->find()->where(['status' => 'queted']);

	    if(!$newsletters->count()) {
		    $io->out('Brak newsletterów do wysłania');
	    }

		foreach ($newsletters as $newsletter) {
			$io->out('Rozpoczynam wysyłanie newslettera - ' . $newsletter->subject);

			if ($this->Newsletters->sendEmailsToAttachedUsers($newsletter)) {
				$io->out('Pomyślnie rozesłano newslettera - ' . $newsletter->subject);
			} else {
				$io->err('Wystąpiły błędy przy wysyłce newsletera - ' . $newsletter->subject);
			}
		}
	    $io->out('Zakończono wysyłkę newsletterów');
    }
}
