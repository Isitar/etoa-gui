<?php

namespace EtoA\Quest;

use LittleCubicleGames\Quests\Definition\Quest\Quest;
use LittleCubicleGames\Quests\Definition\Registry\RegistryInterface;
use LittleCubicleGames\Quests\Definition\Slot\Slot;
use LittleCubicleGames\Quests\Workflow\QuestDefinitionInterface;
use PHPUnit\Framework\TestCase;

class QuestPresenterTest extends TestCase
{
    /** @var QuestPresenter */
    private $presenter;
    private $registry;

    protected function setUp()
    {
        $this->registry = $this->getMockBuilder(RegistryInterface::class)->getMock();
        $this->presenter = new QuestPresenter($this->registry);
    }

    public function testPresent()
    {
        $quest = $this->getMockBuilder(\EtoA\Quest\Entity\Quest::class)->disableOriginalConstructor()->getMock();
        $slot = $this->getMockBuilder(Slot::class)->disableOriginalConstructor()->getMock();
        $questDefinition = $this->getMockBuilder(Quest::class)->disableOriginalConstructor()->getMock();

        $questId = 1;

        $this->registry
            ->expects($this->once())
            ->method('getQuest')
            ->with($this->equalTo($questId))
            ->willReturn($questDefinition);

        $questDefinition
            ->expects($this->once())
            ->method('getData')
            ->willReturn([
                'title' => 'title',
                'description' => 'description',
                'task' => [
                    'description' => 'taskDescription',
                    'operator' => 'equal-to',
                    'value' => 10,
                    'id' => $taskId = 1,
                ],
            ]);

        $quest
            ->expects($this->any())
            ->method('getId')
            ->willReturn($id = 33);

        $quest
            ->expects($this->any())
            ->method('getState')
            ->willReturn(QuestDefinitionInterface::STATE_AVAILABLE);

        $quest
            ->expects($this->any())
            ->method('getQuestId')
            ->willReturn($questId);

        $quest
            ->expects($this->once())
            ->method('getUser')
            ->willReturn($userId = 12);

        $quest
            ->expects($this->once())
            ->method('getProgressMap')
            ->willReturn([
                $taskId => $progress = 2,
            ]);

        $expected = [
            'id' => $id,
            'canClose' => false,
            'questId' => $questId,
            'user' => $userId,
            'title' => 'title',
            'description' => 'description',
            'taskDescription' => 'taskDescription',
            'transition' => [
                'name' => 'Starten',
                'transition' => QuestDefinitionInterface::TRANSITION_START,
            ],
            'taskProgress' => [
                ['maxProgress' => 10, 'progress' => $progress],
            ],
            'rewards' => [
            ],
        ];
        $result = $this->presenter->present($quest, $slot);

        $this->assertEquals($expected, $result);
    }
}
