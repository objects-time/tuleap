<?php
// @codingStandardsIgnoreFile
// @codeCoverageIgnoreStart
// this is an autogenerated file - do not edit
function autoloadf565bff936e24cf6edb06abbed94e316($class) {
    static $classes = null;
    if ($classes === null) {
        $classes = array(
            'paginatedcampaignsrepresentations' => '/Trafficlights/REST/v1/PaginatedCampaignsRepresentations.php',
            'trafficlights\\service' => '/Trafficlights/Service.class.php',
            'trafficlights_rest_resourcesinjector' => '/Trafficlights/REST/ResourcesInjector.class.php',
            'trafficlightsplugin' => '/trafficlightsPlugin.class.php',
            'trafficlightsplugindescriptor' => '/TrafficlightsPluginDescriptor.class.php',
            'trafficlightsplugininfo' => '/TrafficlightsPluginInfo.class.php',
            'tuleap\\trafficlights\\admincontroller' => '/Trafficlights/AdminController.class.php',
            'tuleap\\trafficlights\\adminpresenter' => '/Trafficlights/AdminPresenter.class.php',
            'tuleap\\trafficlights\\agiledashboardpaneinfo' => '/Trafficlights/AgileDashboardPaneInfo.class.php',
            'tuleap\\trafficlights\\artifactdao' => '/Trafficlights/ArtifactDao.php',
            'tuleap\\trafficlights\\artifactfactory' => '/Trafficlights/ArtifactFactory.php',
            'tuleap\\trafficlights\\config' => '/Trafficlights/Config.class.php',
            'tuleap\\trafficlights\\configconformancevalidator' => '/Trafficlights/ConfigConformanceValidator.class.php',
            'tuleap\\trafficlights\\criterion\\isearchonmilestone' => '/Trafficlights/Criterion/ISearchOnMilestone.php',
            'tuleap\\trafficlights\\criterion\\isearchonstatus' => '/Trafficlights/Criterion/ISearchOnStatus.php',
            'tuleap\\trafficlights\\criterion\\milestoneall' => '/Trafficlights/Criterion/MilestoneAll.php',
            'tuleap\\trafficlights\\criterion\\milestonefilter' => '/Trafficlights/Criterion/MilestoneFilter.php',
            'tuleap\\trafficlights\\criterion\\statusall' => '/Trafficlights/Criterion/StatusAll.php',
            'tuleap\\trafficlights\\criterion\\statusclosed' => '/Trafficlights/Criterion/StatusClosed.php',
            'tuleap\\trafficlights\\criterion\\statusopen' => '/Trafficlights/Criterion/StatusOpen.php',
            'tuleap\\trafficlights\\dao' => '/Trafficlights/Dao.class.php',
            'tuleap\\trafficlights\\firstconfigcreator' => '/Trafficlights/FirstConfigCreator.class.php',
            'tuleap\\trafficlights\\indexcontroller' => '/Trafficlights/IndexController.class.php',
            'tuleap\\trafficlights\\indexpresenter' => '/Trafficlights/IndexPresenter.class.php',
            'tuleap\\trafficlights\\malformedqueryparameterexception' => '/Trafficlights/MalformedQueryParameterException.php',
            'tuleap\\trafficlights\\nature\\naturecoveredbyoverrider' => '/Trafficlights/Nature/NatureCoveredByOverrider.php',
            'tuleap\\trafficlights\\nature\\naturecoveredbypresenter' => '/Trafficlights/Nature/NatureCoveredByPresenter.php',
            'tuleap\\trafficlights\\nocrumb' => '/Trafficlights/NoCrumb.php',
            'tuleap\\trafficlights\\querytocriterionconverter' => '/Trafficlights/QueryToCriterionConverter.php',
            'tuleap\\trafficlights\\rest\\v1\\artifactnodebuilder' => '/Trafficlights/REST/v1/ArtifactNodeBuilder.php',
            'tuleap\\trafficlights\\rest\\v1\\artifactnodedao' => '/Trafficlights/REST/v1/ArtifactNodeDao.php',
            'tuleap\\trafficlights\\rest\\v1\\assignedtorepresentationbuilder' => '/Trafficlights/REST/v1/AssignedToRepresentationBuilder.php',
            'tuleap\\trafficlights\\rest\\v1\\campaigncreator' => '/Trafficlights/REST/v1/CampaignCreator.class.php',
            'tuleap\\trafficlights\\rest\\v1\\campaignrepresentation' => '/Trafficlights/REST/v1/CampaignRepresentation.class.php',
            'tuleap\\trafficlights\\rest\\v1\\campaignrepresentationbuilder' => '/Trafficlights/REST/v1/CampaignRepresentationBuilder.php',
            'tuleap\\trafficlights\\rest\\v1\\campaignsqueryrepresentation' => '/Trafficlights/REST/v1/CampaignsQueryRepresentation.class.php',
            'tuleap\\trafficlights\\rest\\v1\\campaignsresource' => '/Trafficlights/REST/v1/CampaignsResource.class.php',
            'tuleap\\trafficlights\\rest\\v1\\definitionrepresentation' => '/Trafficlights/REST/v1/DefinitionRepresentation.php',
            'tuleap\\trafficlights\\rest\\v1\\definitionrepresentationbuilder' => '/Trafficlights/REST/v1/DefinitionRepresentationBuilder.php',
            'tuleap\\trafficlights\\rest\\v1\\definitionsresource' => '/Trafficlights/REST/v1/DefinitionsResource.class.php',
            'tuleap\\trafficlights\\rest\\v1\\executioncreator' => '/Trafficlights/REST/v1/ExecutionCreator.class.php',
            'tuleap\\trafficlights\\rest\\v1\\executionrepresentation' => '/Trafficlights/REST/v1/ExecutionRepresentation.php',
            'tuleap\\trafficlights\\rest\\v1\\executionrepresentationbuilder' => '/Trafficlights/REST/v1/ExecutionRepresentationBuilder.php',
            'tuleap\\trafficlights\\rest\\v1\\executionsresource' => '/Trafficlights/REST/v1/ExecutionsResource.class.php',
            'tuleap\\trafficlights\\rest\\v1\\nodebuilderfactory' => '/Trafficlights/REST/v1/NodeBuilderFactory.php',
            'tuleap\\trafficlights\\rest\\v1\\nodereferencerepresentation' => '/Trafficlights/REST/v1/NodeReferenceRepresentation.php',
            'tuleap\\trafficlights\\rest\\v1\\noderepresentation' => '/Trafficlights/REST/v1/NodeRepresentation.php',
            'tuleap\\trafficlights\\rest\\v1\\noderesource' => '/Trafficlights/REST/v1/NodesResource.class.php',
            'tuleap\\trafficlights\\rest\\v1\\previousresultrepresentation' => '/Trafficlights/REST/v1/PreviousResultRepresentation.php',
            'tuleap\\trafficlights\\rest\\v1\\projectresource' => '/Trafficlights/REST/v1/ProjectResource.class.php',
            'tuleap\\trafficlights\\rest\\v1\\slicedexecutionrepresentations' => '/Trafficlights/REST/v1/SlicedExecutionRepresentations.php',
            'tuleap\\trafficlights\\router' => '/Trafficlights/Router.class.php',
            'tuleap\\trafficlights\\trafficlightsartifactrightspresenter' => '/Trafficlights/TrafficlightsArtifactRightsPresenter.php',
            'tuleap\\trafficlights\\trafficlightscontroller' => '/Trafficlights/TrafficlightsController.php'
        );
    }
    $cn = strtolower($class);
    if (isset($classes[$cn])) {
        require dirname(__FILE__) . $classes[$cn];
    }
}
spl_autoload_register('autoloadf565bff936e24cf6edb06abbed94e316');
// @codeCoverageIgnoreEnd
