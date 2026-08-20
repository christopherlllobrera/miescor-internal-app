<?php

namespace App\Filament\Clusters\Workflow\WorkflowModules;

use App\Filament\Clusters\Workflow\WorkflowCluster;
use App\Filament\Clusters\Workflow\WorkflowModules\Pages\CreateWorkflowModule;
use App\Filament\Clusters\Workflow\WorkflowModules\Pages\EditWorkflowModule;
use App\Filament\Clusters\Workflow\WorkflowModules\Pages\ListWorkflowModules;
use App\Filament\Clusters\Workflow\WorkflowModules\Schemas\WorkflowModuleForm;
use App\Filament\Clusters\Workflow\WorkflowModules\Tables\WorkflowModulesTable;
use App\Models\WorkflowModule;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class WorkflowModuleResource extends Resource
{
    protected static ?string $model = WorkflowModule::class;

    protected static ?string $cluster = WorkflowCluster::class;

    protected static ?string $navigationLabel = 'Workflows';

    protected static ?int $navigationSort = 1;

    protected static ?string $breadcrumb = 'Workflows';

    public static function form(Schema $schema): Schema
    {
        return WorkflowModuleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WorkflowModulesTable::configure($table);
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
            'index' => ListWorkflowModules::route('/'),
            'create' => CreateWorkflowModule::route('/create'),
            'edit' => EditWorkflowModule::route('/{record}/edit'),
        ];
    }
}
