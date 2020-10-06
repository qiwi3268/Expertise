<div class="view-body card-form card">
    
    <div class="card-form__header card-expand">
<!--        --><?php //if ($variablesTV->getValue('block1_completed')): ?>
            <i class="card-form__icon-state fas fa-check-circle valid"></i>
<!--        --><?php //else: ?>
<!--            <i class="card-form__icon-state fas fa-exclamation-circle"></i>-->
<!--        --><?php //endif; ?>
        <span class="card-form__title">СВЕДЕНИЯ О ЗАЯВИТЕЛЕ</span>
        <i class="card-form__icon-expand fas fa-chevron-down arrow-down card-icon"></i>
    </div>
    
    <div class="card-form__body expanded card-body">
        <div class="card-form__financing">
        
            <?php \Lib\Singles\TemplateMaker::requireByName('view_financing_sources'); ?>
    
        </div>
    </div>
    
    <div class="card-form__header card-expand">
        <!--        --><?php //if ($variablesTV->getValue('block1_completed')): ?>
<!--        <i class="card-form__icon-state fas fa-check-circle valid"></i>-->
        <!--        --><?php //else: ?>
                    <i class="card-form__icon-state fas fa-exclamation-circle"></i>
        <!--        --><?php //endif; ?>
        <span class="card-form__title">СВЕДЕНИЯ О ЗАЯВИТЕЛЕ</span>
        <i class="card-form__icon-expand fas fa-chevron-down arrow-down card-icon"></i>
    </div>
    
    <div class="card-form__body expanded card-body">
        <div class="card-form__financing">
            
            <?php \Lib\Singles\TemplateMaker::requireByName('view_financing_sources'); ?>
        
        </div>
    </div>
    
</div>