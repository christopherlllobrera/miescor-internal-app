<?php

namespace App\Filament\Clusters\Workflow\WorkflowTagModules;

use App\Filament\Clusters\Workflow\WorkflowCluster;
use App\Filament\Clusters\Workflow\WorkflowTagModules\Pages\CreateWorkflowTagModule;
use App\Filament\Clusters\Workflow\WorkflowTagModules\Pages\EditWorkflowTagModule;
use App\Filament\Clusters\Workflow\WorkflowTagModules\Pages\ListWorkflowTagModules;
use App\Filament\Clusters\Workflow\WorkflowTagModules\Schemas\WorkflowTagModuleForm;
use App\Filament\Clusters\Workflow\WorkflowTagModules\Tables\WorkflowTagModulesTable;
use App\Models\WorkflowTagModule;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class WorkflowTagModuleResource extends Resource
{
    protected static ?string $model = WorkflowTagModule::class;

    protected static ?string $cluster = WorkflowCluster::class;

    protected static ?string $navigationLabel = 'Tags';

    protected static ?int $navigationSort = 2;

    protected static ?string $breadcrumb = 'Tags';

    public static function form(Schema $schema): Schema
    {
        return WorkflowTagModuleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WorkflowTagModulesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWorkflowTagModules::route('/'),
            // 'create' => CreateWorkflowTagModule::route('/create'),
            // 'edit' => EditWorkflowTagModule::route('/{record}/edit'),
        ];
    }
}
