<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class GenerateUserCommand extends Command
{
    protected static $defaultName = 'app:generate:user';
    /**
     * @var EntityManagerInterface
     */
    private $manager;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;
    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(
        EntityManagerInterface $manager,
        UserPasswordEncoderInterface $encoder,
        ValidatorInterface $validator
    ) {
        parent::__construct();
        $this->manager = $manager;
        $this->encoder = $encoder;
        $this->validator = $validator;
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('firstname', InputArgument::OPTIONAL, 'Specify the firstname of the user')
            ->addArgument('lastname', InputArgument::OPTIONAL, 'Specify the firstname of the user')
            ->addArgument('email', InputArgument::OPTIONAL, 'Specify the email of the user')
            ->addArgument('password', InputArgument::OPTIONAL, 'Specify the password of the user')
            ->addArgument('roles', InputArgument::OPTIONAL | InputArgument::IS_ARRAY, 'Specify the roles of the user')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $user = new User();

        [$firstname, $lastname, $email, $password, $roles] = [
            $input->getArgument('firstname'),
            $input->getArgument('lastname'),
            $input->getArgument('email'),
            $this->encoder->encodePassword($user, $input->getArgument('password')),
            $input->getArgument('roles')
        ];

        $user
            ->setEmail($email)
            ->setFirstname($firstname)
            ->setLastname($lastname)
            ->setPassword($password)
            ->setRoles($roles);

        $errors = $this->validator->validate($user);
        if(\count($errors) > 0) {
            $io->error((string) $errors);
            return;
        }

        $this->manager->persist($user);
        $this->manager->flush();

        $io->success('Successfuly persisted ' . $user . ' with id "' . $user->getId() . '"');
    }
}
