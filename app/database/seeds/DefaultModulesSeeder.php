<?php

// Builds an initial modules structure

use ActivityTemplates\StoryItem;
use ActivityTemplates\CartoonPicture;
use ActivityTemplates\YesNoSection;
use ActivityTemplates\YesNoOption;
use ActivityTemplates\CalculationItem;

class DefaultModulesSeeder extends Seeder {

    public function run() {
        // Creating a module
        $module = new Module(array(
            'title' => "It's For Real",
            'skin' => 'IfrModuleSkin'
        ));
        $module->save();

        // Creating series
        $series = array(
            new Series(array('title' => 'Series I')),
            new Series(array('title' => 'Series II')),
            new Series(array('title' => 'Series III'))
        );
        $module->series()->saveMany($series);

        // Creating lessons
        $lessons = array(
            new Lesson(array('title' => 'What\'s the Big Deal?')),
            new Lesson(array('title' => 'Work on a Day Like This?')),
            new Lesson(array('title' => 'Was It My Fault?'))
        );
        Series::find(1)->lessons()->saveMany($lessons);

        // Creating activities
        $this->seedActivities(Lesson::find(1));
    }

    protected function seedActivities($lesson) {
        $this->seedFreeFormAnswer($lesson);
        $this->seedSelect($lesson);
        $this->seedStory($lesson);
        $this->seedCartoon($lesson);
        $this->seedYesNo($lesson);
        $this->seedBlog($lesson);
        $this->seedCalculation($lesson);
        $this->seedMultiAnswers($lesson);
        $this->seedQnA($lesson);
    }

    protected function seedFreeFormAnswer($lesson) {
        $activityTemplate = new ActivityTemplates\FreeFormAnswer(array(
            'description' => 'If you were in Zoe’s situation, how would you react to the friends?'
        ));
        $activityTemplate->save();

        $activity = new Activity(array(
            'title' => 'What would you do?',
            'background_image' => 'lightbulbs.jpg',
            'illustration_image' => '',
            'audio_version' => '',
            'pdf_version' => '',
            'feedback' => ''
        ));

        $activity->lesson()->associate($lesson);
        $activity->template()->associate($activityTemplate);

        $activity->save();
    }

    protected function seedSelect($lesson) {
        $activityTemplate = new ActivityTemplates\Select(array(
            'description' => ''
        ));
        $activityTemplate->save();

        $activityTemplate->options()->saveMany(array(
            new ActivityTemplates\SelectOption(array('option' => 'I will lose money if Zoe’s friends don’t pay.')),
            new ActivityTemplates\SelectOption(array('option' => 'I will be very upset with Zoe.')),
            new ActivityTemplates\SelectOption(array('option' => 'I won’t be able to trust Zoe in the future.')),
            new ActivityTemplates\SelectOption(array('option' => 'I will give her a warning about losing her job.')),
            new ActivityTemplates\SelectOption(array('option' => 'I will wonder if other employees are giving away food.')),
            new ActivityTemplates\SelectOption(array('option' => 'I will deduct the cost of the pizza from Zoe’s pay.')),
            new ActivityTemplates\SelectOption(array('option' => 'My relationship with Zoe will suffer.')),
            new ActivityTemplates\SelectOption(array('option' => 'I won’t grant Zoe any special favors.')),
        ));

        $activity = new Activity(array(
            'title' => 'What the Boss would think If Zoe gives away free pizzas',
            'background_image' => 'pizza2.jpg',
            'illustration_image' => 'boss.jpg',
            'audio_version' => '',
            'pdf_version' => '',
            'feedback' => ''
        ));

        $activity->lesson()->associate($lesson);
        $activity->template()->associate($activityTemplate);

        $activity->save();
    }

    protected function seedStory($lesson) {
        $activityTemplate = new ActivityTemplates\Story(array(
            'title' => 'Pizza Predicament'
        ));
        $activityTemplate->save();

        // Creating some characters for the story
        $characters = array();

        foreach (array(
    array('name' => 'Ryan', 'picture' => 'user1.png'),
    array('name' => 'Mateo', 'picture' => 'ryan.png'),
    array('name' => 'Kaden', 'picture' => 'kaden.png'),
    array('name' => 'Zoe', 'picture' => 'zoe.png'),
        ) as $character) {
            $character = ActivityTemplates\StoryCharacter::create($character);
            $characters[] = $character->id;
        }

        // Creating story items
        $activityTemplate->items()->saveMany(array(
            new StoryItem(array(
                'text' => '“Hey, Mateo,” yells Ryan as he walks into the math room. “Did you hear about the skateboard park they’re building in town?”',
                'character_id' => $characters[0],
                'is_right_side' => 1,
                    )),
            new StoryItem(array(
                'text' => 'Mateo pumps his fist and shouts excitedly, “Awesome! When? I need some new challenges with my board.”',
                'character_id' => $characters[1],
                    )),
            new StoryItem(array(
                'text' => '“My brother said the park’s supposed to be ready by summer. He heard it will have amazing ramps and rails."',
                'character_id' => $characters[0],
                'is_right_side' => 1,
                    )),
            new StoryItem(array(
                'text' => 'Later that afternoon, Mateo and Ryan meet their friends Kaden and Lily at Carlo’s Pizza, where Zoe works. Above the noise Mateo shouts, “Hey, Zoe, did you hear about the new skateboard park that’s coming to town? I’m going to conquer it! Bring us a large pepperoni in a hurry. I have to get to work.”',
                'character_id' => $characters[1],
                    )),
            new StoryItem(array(
                'text' => 'Ten minutes later, Zoe brings out a large pepperoni pizza, four drinks, and the check. “Whose check is that, Zoe?” teases Kaden. “You aren’t going to make us pay, are you? Your boss isn’t around, so he’ll never know.”',
                'character_id' => $characters[2],
                'is_right_side' => 1,
                    )),
            new StoryItem(array(
                'text' => '“Look, Kaden, you’re not funny. If you want this pizza, you have to pay for it,” snaps Zoe, who is rushed with customers. “If you are really my friend, you won’t ask that, even if you don’t mean it. I could get in a lot of trouble if I give you a free pizza. That’s the same as stealing. I could lose my job.”',
                'character_id' => $characters[3],
                    )),
            new StoryItem(array(
                'text' => '“Zoe, what’s your problem?” jokes Mateo. “It’s not like we’re asking you to commit murder. We’re only talking about one little pizza. What’s the big deal? ”',
                'character_id' => $characters[1],
                'is_right_side' => 1,
                    ))
        ));

        // Saving the activity
        $activity = new Activity(array(
            'title' => 'Pizza Predicament',
            'background_image' => 'pizza.jpg',
            'illustration_image' => 'kids.jpg',
            'audio_version' => '',
            'pdf_version' => '',
            'feedback' => ''
        ));

        $activity->lesson()->associate($lesson);
        $activity->template()->associate($activityTemplate);

        $activity->save();
    }

    protected function seedCartoon($lesson) {
        $activityTemplate = new ActivityTemplates\Cartoon(array());
        $activityTemplate->save();

        $activityTemplate->pictures()->saveMany(array(
            new CartoonPicture(array('file' => 'comic1.jpg')),
            new CartoonPicture(array('file' => 'comic2.jpg')),
            new CartoonPicture(array('file' => 'comic3.jpg')),
            new CartoonPicture(array('file' => 'comic4.jpg'))
        ));

        $activity = new Activity(array(
            'title' => 'Comic',
            'background_image' => 'comic-bg.png',
            'illustration_image' => 'comic-kids.png',
            'audio_version' => '',
            'pdf_version' => '',
            'feedback' => ''
        ));

        $activity->lesson()->associate($lesson);
        $activity->template()->associate($activityTemplate);

        $activity->save();
    }

    protected function seedYesNo($lesson) {
        $activityTemplate = new ActivityTemplates\YesNo(array(
            'description' => "Every year in the U.S. people who consider themselves “honest” steal millions of dollars in goods and services from their employers. In most cases, employees don’t think twice about taking small items like Post-It Notes™ or pens. But taking unauthorized items is the same as stealing and lowers a company’s profits.\n\nMost workplace dishonesty occurs when employees lie, steal, or behave in other unethical ways. One student was fired from her part-time job when she rang up one pair of jeans for her boyfriend, but bagged two pairs for him. Look at the following workplaces and place an “X” if the item taken would be considered stolen and an “O” if it would not be considered stolen."
        ));
        $activityTemplate->save();

        $activityTemplate->sections()->saveMany(array(
            new YesNoSection(array('title' => 'Restaurant')),
            new YesNoSection(array('title' => 'Business Office')),
            new YesNoSection(array('title' => 'Movie Theater')),
            new YesNoSection(array('title' => 'Hospital')),
            new YesNoSection(array('title' => 'Clothing Store')),
            new YesNoSection(array('title' => 'Drugstore')),
        ));

        // Adding options to sections
        $activityTemplate->sections[0]->options()->saveMany(array(
            new YesNoOption(array('option' => 'Bottle of water from customer refrigerator')),
            new YesNoOption(array('option' => 'Paper cup of tap water')),
            new YesNoOption(array('option' => 'Package of paper plates')),
        ));

        $activityTemplate->sections[1]->options()->saveMany(array(
            new YesNoOption(array('option' => 'One package of Post-It™ notes')),
            new YesNoOption(array('option' => 'Felt-tip pen for company work you’ll do at home')),
            new YesNoOption(array('option' => 'Using company credit card for personal gas')),
        ));

        $activityTemplate->sections[2]->options()->saveMany(array(
            new YesNoOption(array('option' => 'Candy bar')),
            new YesNoOption(array('option' => 'Letting a family member into a movie without paying')),
            new YesNoOption(array('option' => 'Running over break time to answer a text message')),
        ));

        $activityTemplate->sections[3]->options()->saveMany(array(
            new YesNoOption(array('option' => 'Arm sling from the inventory closet')),
            new YesNoOption(array('option' => 'Band-Aid™ from employee lounge')),
            new YesNoOption(array('option' => 'Unopened aspirin from the pharmacy')),
        ));

        $activityTemplate->sections[4]->options()->saveMany(array(
            new YesNoOption(array('option' => 'Using your discount for a friend’s purchase')),
            new YesNoOption(array('option' => 'Taking home discarded clothes hangers')),
            new YesNoOption(array('option' => 'Hiding from customers a full-priced item you’ll buy at discount')),
        ));

        $activityTemplate->sections[5]->options()->saveMany(array(
            new YesNoOption(array('option' => 'This week’s issue of a celebrity magazine')),
            new YesNoOption(array('option' => 'A package of candy')),
            new YesNoOption(array('option' => 'Punching in the wrong start time for work')),
        ));

        $activity = new Activity(array(
            'title' => 'Dishonesty at Work',
            'background_image' => 'pizza.jpg',
            'illustration_image' => '',
            'audio_version' => '',
            'pdf_version' => '',
            'feedback' => ''
        ));

        $activity->lesson()->associate($lesson);
        $activity->template()->associate($activityTemplate);

        $activity->save();
    }

    protected function seedBlog($lesson) {
        $activityTemplate = new ActivityTemplates\Blog(array(
            'title' => "The Scenario for the blog goes here...",
            'explanation' => ''
        ));
        $activityTemplate->save();

        $activity = new Activity(array(
            'title' => 'Blog about It!',
            'background_image' => 'blog.jpg',
            'illustration_image' => '',
            'audio_version' => '',
            'pdf_version' => '',
            'feedback' => ''
        ));

        $activity->lesson()->associate($lesson);
        $activity->template()->associate($activityTemplate);

        $activity->save();
    }

    protected function seedCalculation($lesson) {
        $activityTemplate = new ActivityTemplates\Calculation(array(
            'description' => "Fun & Fit employs 16 people, and is open every day of the year. The employees work hard, but some take things, come in to work late or stay too long at lunch. Calculate the cost of dishonesty in one day each day if the following items are taken.",
            'name' => "Fun & Fit"
        ));
        $activityTemplate->save();

        $activityTemplate->items()->saveMany(array(
            new CalculationItem(array(
                'name' => 'Two pens',
                'employer_cost' => '1.89',
                'cost_unit' => 'each',
                    )),
            new CalculationItem(array(
                'name' => '12 personal photocopies',
                'employer_cost' => '0.05',
                'cost_unit' => 'per copy',
                    )),
            new CalculationItem(array(
                'name' => '30 minutes spent in casual conversation',
                'employer_cost' => '9.50',
                'cost_unit' => 'per hour',
                    )),
            new CalculationItem(array(
                'name' => '10 minutes late to work',
                'employer_cost' => '9.50',
                'cost_unit' => 'per hour',
                    )),
            new CalculationItem(array(
                'name' => '15 minutes late returning from lunch',
                'employer_cost' => '9.50',
                'cost_unit' => 'per hour',
                    )),
            new CalculationItem(array(
                'name' => '5 minutes early departure time from work',
                'employer_cost' => '9.50',
                'cost_unit' => 'per hour',
                    )),
            new CalculationItem(array(
                'name' => 'One tee shirt',
                'employer_cost' => '14',
                'cost_unit' => '',
                    )),
            new CalculationItem(array(
                'name' => 'One health bar',
                'employer_cost' => '2.49',
                'cost_unit' => '',
                    )),
            new CalculationItem(array(
                'name' => 'One bottle of water',
                'employer_cost' => '1.25',
                'cost_unit' => '',
                    )),
        ));

        $activity = new Activity(array(
            'title' => 'Behind the Scenes',
            'background_image' => 'pizza.jpg',
            'illustration_image' => '',
            'audio_version' => '',
            'pdf_version' => '',
            'feedback' => ''
        ));

        $activity->lesson()->associate($lesson);
        $activity->template()->associate($activityTemplate);

        $activity->save();
    }

    protected function seedMultiAnswers($lesson) {
        $activityTemplate = new ActivityTemplates\MultipleAnswers(array(
            'description' => "List some of the reasons employees give for taking products or using services they don’t pay for.",
            'number_of_fields' => 5,
            'placeholder_answers' => json_encode(array('“It’s only a thumb drive. No one will care.”', '', '', '', ''))
        ));
        $activityTemplate->save();

        $activity = new Activity(array(
            'title' => 'Excuses, Excuses, Excuses',
            'background_image' => 'pizza.jpg',
            'illustration_image' => '',
            'audio_version' => '',
            'pdf_version' => '',
            'feedback' => ''
        ));

        $activity->lesson()->associate($lesson);
        $activity->template()->associate($activityTemplate);

        $activity->save();
    }

    protected function seedQnA($lesson) {
        $activityTemplate = new ActivityTemplates\QnA(array(
            'title' => 'Ask Rory'
        ));
        $activityTemplate->save();

        // Creating some characters for the story
        $characters = array();

        foreach (array(
    array('name' => 'Worried', 'picture' => 'question2.png'),
    array('name' => 'Willing to Help', 'picture' => 'question2.png'),
    array('name' => 'Rory', 'picture' => 'answer2.png')
        ) as $character) {
            $character = ActivityTemplates\StoryCharacter::create($character);
            $characters[] = $character->id;
        }

        // Creating story items
        $activityTemplate->items()->saveMany(array(
            new StoryItem(array(
                'text' => 'Last night, I went to a career fair to talk with employers about jobs. Before I left work, I made several copies of my résumé to take with me. My boss looked at me funny when I walked out the door. Did I do anything wrong?',
                'character_id' => $characters[0],
                'is_right_side' => 0,
                    )),
            new StoryItem(array(
                'text' => 'Did you ask your boss for permission? If not, keep your hands off the copier. Each business has its own policy about making personal copies. Ask what it is.',
                'character_id' => $characters[2],
                'is_right_side' => 1,
                    )),
            new StoryItem(array(
                'text' => 'A person I work with is really nice, and I like him. The problem is he comes to work late sometimes because he has to drop his daughter off at school. He asks me to punch his time card so he won’t get in trouble for being late. What should I do?',
                'character_id' => $characters[1],
                    )),
            new StoryItem(array(
                'text' => 'You both could get fired because he is stealing time from your company and you are being dishonest. Tell your friend to talk with your boss and work out a compromise. Maybe he could stay after work to make up the time.',
                'character_id' => $characters[2],
                'is_right_side' => 1,
                    ))
        ));

        // Saving the activity
        $activity = new Activity(array(
            'title' => 'Ask Rory',
            'background_image' => 'ask.jpg',
            'illustration_image' => '',
            'audio_version' => '',
            'pdf_version' => '',
            'feedback' => ''
        ));

        $activity->lesson()->associate($lesson);
        $activity->template()->associate($activityTemplate);

        $activity->save();
    }

}
