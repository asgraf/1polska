<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Vote|null $my_vote
 * @var int $upvote_count
 * @var int $downvote_count
 * @var \Cake\Collection\Iterator\FilterIterator|\App\Model\Entity\Vote[] $grouped_upvotes
 * @var \Cake\Collection\Iterator\FilterIterator|\App\Model\Entity\Vote[] $grouped_downvotes
 * @var \Cake\Datasource\EntityInterface $entity
 */
?>
<div class="row">
	<div class="col-md-6 ">
		<?php
		echo $this->Panel->create(__('Popierają') . ' (' . $upvote_count . ' ' . __n('głos', 'głosów', $upvote_count) . ')', ['type' => 'success']);
		if ($grouped_upvotes->isEmpty()) {
			echo __('Brak głosów za');
		}
		foreach ($grouped_upvotes as $grouped_upvote) {
			$comment = h($grouped_upvote['comment']) ?: __('Brak uzasadnienia');
			echo '<div>' . $grouped_upvote->get('item_count') . ' ' . __n('głos', 'głosów', $grouped_upvote->get('item_count')) . ' - ' . $comment . '</div>';
		}
		echo $this->Panel->end();
		?>
	</div>
	<div class="col-md-6">
		<?php
		echo $this->Panel->create(__('Nie popierają') . ' (' . $downvote_count . ' ' . __n('głos', 'głosów', $downvote_count) . ')', ['type' => 'danger']);
		if ($grouped_downvotes->isEmpty()) {
			echo __('Brak głosów przeciw');
		}
		foreach ($grouped_downvotes as $grouped_downvote) {
			echo '<div class="data-markdown">' . h($grouped_downvote['comment']) . '</div>';
		}
		echo $this->Panel->end();
		?>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<?php
		echo $this->Panel->create(__('Twój głos'), ['type' => 'primary']);
		echo $this->Form->create($my_vote, [
			'url' => [
				'controller' => $entity->getSource(),
				'action' => 'vote',
				'id' => $entity->id
			]
		]);
		echo $this->Form->control('value', [
			'label' => false,
			'type' => 'radio',
			'inline' => true,
			'required',
			'options' => ['1' => __('Popieram'), '-1' => __('Nie popieram')]
		]);
		echo $this->Form->control('comment', [
			'label' => 'Krótkie uzasadnienie twojego głosu',
			'type' => 'textarea',
			'maxlength' => 250,
			'placeholder' => __('Brak uzasadnienia')
		]);
		echo $this->Form->button(__('Zatwierdź'), ['class' => 'btn-primary']);
		echo $this->Form->end();
		echo $this->Panel->end();
		?>
	</div>
</div>
