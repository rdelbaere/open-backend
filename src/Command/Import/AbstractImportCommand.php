<?php

namespace App\Command\Import;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Yaml\Yaml;

abstract class AbstractImportCommand extends Command
{
    private string $rootPath;

    public function __construct(
        private EntityManagerInterface $em,
        private ParameterBagInterface $parameterBag,
        string $name = null
    ){
        parent::__construct($name);
        $this->rootPath = $this->parameterBag->get('kernel.project_dir');
    }

    protected function configure(): void
    {
        $this
            ->addArgument('path', InputArgument::OPTIONAL, 'Relative path ', $this->getDefaultFile())
            ->addOption('purge', 'p', InputOption::VALUE_NONE, 'Purge current data before importation')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if ($input->getOption('purge')) {
            $this->purge();

            $message = sprintf('All %s entities purged!', $this->getType());
            $io->info($message);
        }

        $data = $this->loadData($input->getArgument('path'));
        $this->buildMany($data);

        $message = sprintf('%d %s entities imported!', count($data), $this->getType());
        $io->success($message);
        return Command::SUCCESS;
    }

    private function purge()
    {
        $repository = $this->em->getRepository($this->getClass());
        $repository->purge();
    }

    private function loadData(string $relativePath): array
    {
        $path = sprintf('%s/%s', $this->rootPath, $relativePath);
        $file = Yaml::parseFile($path);
        return $file['entries'];
    }

    private function buildMany(array $data): void
    {
        $class = $this->getClass();

        foreach($data as $key => $row){
            $this->buildOne($class, $row);

            if($key % 100 === 0){
                $this->flush();
            }
        }

        $this->flush();
    }

    private function buildOne(string $class, array $data)
    {
        $entity = new $class();

        foreach($data as $property => $value){
            $this->buildProperty($entity, $property, $value);
        }

        $this->em->persist($entity);
    }

    private function buildProperty($entity, $property, $value)
    {
        $hook = sprintf('hook%s', ucfirst($property));
        if(method_exists($this, $hook)){
            $this->$hook($entity, $value);
        }else if(!empty($value)){
            $setter = sprintf('set%s', ucfirst($property));
            if(method_exists($entity, $setter)){
                $entity->$setter($value);
            }
        }
    }

    private function flush(): void
    {
        $this->em->flush();
        $this->em->clear();
    }

    private function getType(): string
    {
        $fragements = explode('\\', $this->getClass());
        return array_pop($fragements);
    }

    abstract protected function getClass(): string;
    abstract protected function getDefaultFile(): string;
}
