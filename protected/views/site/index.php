<div id="login-dark-banner">
    <?php echo CHtml::image('../images/searchDocs.png', '', array('style' => 'height:50px;float:left')) ?>
    <h2>Buscar</h2>
</div>
<div id="login-dark-banner-wrap"></div>
<?php $this->pageTitle = Yii::app()->name; ?>
<div class="container">
    <div id="content">
        <div id="first-search">
            <div class="by-doc-type">
                <fieldset class="form">
                    <legend>Por tipo de documento</legend>
                    <div class="search">
                        <div class="level-tag">Nivel 1</div>
                        <div class="ddlsearch"><?php echo CHtml::dropDownList('docLevel1', '0', $docLevel1); ?></div>
                    </div>
                    <div class="search">
                        <div class="level-tag">Nivel 2</div>
                        <div class="ddlsearch"><?php echo CHtml::dropDownList('docLevel2', '0', $docLevel2); ?></div>
                    </div>
                    <div class="search">
                        <div class="level-tag">Nivel 3</div>
                        <div class="ddlsearch"><?php echo CHtml::dropDownList('docLevel3', '0', $docLevel3); ?></div>
                    </div>
                    <div class="search">
                        <div class="level-tag">Nivel 4</div>
                        <div class="ddlsearch"><?php echo CHtml::dropDownList('docLevel4', '0', $docLevel4); ?></div>
                    </div>
                    <div class="login-form" style="float:right;margin-right: 40px;">
                        <button type="submit" name="Submit" onClick="toggleFirstSearch();">
                            <?php echo CHtml::image('../images/filter.png', 'Ingresar'); ?>
                            Continuar
                        </button>
                    </div>
                </fieldset>
            </div>
            <div class="by-doc-type">
                <fieldset class="form">
                    <legend>Búsqueda general</legend>
                    <div class="search">
                        <div class="level-tag">Buscará en todos los campos definidos para búsqueda especial.</div>
                        <div class="login-form" style="float:right;margin-right: 40px;">
                            <button type="submit" name="Submit2" onClick="toggleGeneralSearch();">
                                <?php echo CHtml::image('../images/filter.png', 'Ingresar'); ?>
                                Continuar
                            </button>
                        </div>
                    </div>
                </fieldset>
            </div>
            <div class="by-doc-type">
                <fieldset class="form">
                    <legend>Por rótulos</legend>
                    <div class="search">
                        <div class="level-tag">Nivel 1</div>
                        <div class="ddlsearch"><?php echo CHtml::dropDownList('rotulo', '0', $rotulos); ?></div>
                        <div class="login-form" style="float:right;margin-right: 40px;">
                            <button type="submit" onClick="toggleRotulosSearch()" name="Submit3" >
                                <?php echo CHtml::image('../images/filter.png', 'Ingresar'); ?>
                                Continuar
                            </button>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
        <div id="second-search">
            <div id="second-search-left">
                <div id="buttons" style="float:left;width: 260px;padding: 30px 0 0 0;">
                    <div class="login-form" style="float:left;">
                        <button class="back" style="margin:0 10px 0 0;" type="submit" name="Submit2" onClick="goBack()();">
                            <?php echo CHtml::image('../images/back.png', 'Ingresar'); ?>
                            Volver
                        </button>
                    </div>
                    <div class="login-form" style="float:left;">
                        <button style="margin:0 10px 0 0;" type="submit" name="Submit2" onClick="SearchDocs();">
                            <?php echo CHtml::image('../images/xmag.png', 'Ingresar'); ?>
                            Buscar
                        </button>
                    </div>
                </div>
                <div id="filters" style="float:left;">
                    <fieldset class="form" style="width:260px;">
                        <legend>Se esta buscando por:</legend>
                        <div id="filterLevel1" class="level-tag"></div>
                        <div id="filterLevel2" class="level-tag"></div>
                        <div id="filterLevel3" class="level-tag"></div>
                        <div id="filterLevel4" class="level-tag"></div>
                        <div id="searchType" style="width: 260px;float:left;padding: 20px 0 10px 0">
                            <div style="float:left;width:100%;padding: 0 0 10px 10px;"><?php echo CHtml::label('Tipo de Busqueda', 'searchType', array('style' => 'text-align: left;')) ?></div>
                            <?php echo CHtml::radioButtonList('searchType', '0', array('0' => 'Exacta', '1' => 'Parecida'), array('style' => 'width:50px;float:left;', 'labelOptions' => array('style' => 'width:60px;text-align: left;'), 'separator' => " ")) ?>
                        </div>
                    </fieldset>
                </div>

                <!-- Bloque 2 -->

                <div id="filtersMeta" style="float:left;">
                    <div id="filtersCarat">
                        <fieldset class="form" style="width:260px;">
                            <legend>Metadata de carátula</legend>
                            <div id="MetaCarats" style="width: 260px;"></div>
                        </fieldset>
                    </div>
                    <div id="filtersOCR">
                        <fieldset class="form" style="width:260px;">
                            <legend>Metadata de imagen</legend>
                            <div id="MetaOCRs" style="width: 260px;"></div>
                        </fieldset>
                    </div>
                </div>


                <div id="buttons" style="float:left;width: 260px;padding: 30px 0 0 0;">
                    <div class="login-form" style="float:left;">
                        <button style="margin:0 10px 0 0;" type="submit" name="Submit2" onClick="goBack();">
                            <?php echo CHtml::image('../images/back.png', 'Ingresar'); ?>
                            Volver
                        </button>
                    </div>
                    <div class="login-form" style="float:left;">
                        <button style="margin:0 10px 0 0;" type="submit" name="Submit2" onClick="SearchDocs();">
                            <?php echo CHtml::image('../images/xmag.png', 'Ingresar'); ?>
                            Buscar
                        </button>
                    </div>
                </div>
            </div>
            <div id="second-search-right" style="padding-left:10px;">
                <div id="searchType" style="margin-left: 20px;width:100%;float:left;padding: 20px 0 0 0">
                    <div style="float:left;width:100%;padding: 0px;">
                        <?php echo CHtml::label('Agrupar por', 'searchType', array('style' => 'text-align: left;')) ?>

                        <?php echo CHtml::radioButtonList('groupType', '0', array('0' => 'Carátula', '1' => 'Imagen'), array('onClick' => 'checkImageSelection()', 'style' => 'width:50px;float:left;', 'labelOptions' => array('style' => 'width:60px;text-align: left;'), 'separator' => " ")) ?>

                    </div>
                </div>
                <fieldset class="form" style="width:auto">
                    <legend>Resultados</legend>
                    <div id="results">

                    </div>
                </fieldset>
            </div>

        </div>
        <!--			Busqueda general-->
        <div id="second-general-search" style="display:none;">
            <div id="second-search-left">
                <div id="buttons" style="float:left;width: 260px;padding: 30px 0 0 0;">
                    <div class="login-form" style="float:left;">
                        <button style="margin:0 10px 0 0;" type="submit" name="Submit2" onClick="SearchGralDocs();">
                            <?php echo CHtml::image('../images/xmag.png', 'Ingresar'); ?>
                            Buscar
                        </button>
                    </div>
                    <div class="login-form" style="float:left;">
                        <button style="margin:0 10px 0 0;" type="submit" name="Submit2" onClick="goBack();">
                            <?php echo CHtml::image('../images/back.png', 'Ingresar'); ?>
                            Volver
                        </button>
                    </div>
                </div>
                <div id="searchBox">
                    <div id="filterField" style="float:left;">
                        <fieldset class="form" style="width:260px;">
                            <legend>Búsqueda general</legend>
                            <div id="searchType" style="width: 260px;float:left;">
                                <div style="float:left;width:100%;padding: 0 0 10px 10px;"><?php echo CHtml::label('Tipo de Busqueda', 'searchType', array('style' => 'text-align: left;')) ?></div>
                                <?php echo CHtml::radioButtonList('searchGeneralType', '0', array('0' => 'Exacta', '1' => 'Parecida'), array('style' => 'width:50px;float:left;', 'labelOptions' => array('style' => 'width:60px;text-align: left;'), 'separator' => " ")) ?>
                            </div>
                            <div id="field" style="width: 250px;padding-left:10px">
                                <?php echo CHtml::label('Buscar', 'searchField', array('style' => 'text-align:left;')) ?>
                                <?php echo CHtml::textField('searchField') ?>
                            </div>
                        </fieldset>
                    </div>
                </div>
                <div id="filterBox">
                    <div id="filter" style="float:left;">
                        <fieldset class="form" style="width:260px;">
                            <legend>Restringir</legend>
                            <div id="filterRestict" style="width: 260px;padding-left:10px;">
                            </div>
                        </fieldset>
                    </div>
                </div>
                <div id="buttons" style="float:left;width: 260px;padding: 30px 0 0 0;">
                    <div class="login-form" style="float:left;">
                        <button style="margin:0 10px 0 0;" type="submit" name="Submit2" onClick="SearchGralDocs();">
                            <?php echo CHtml::image('../images/xmag.png', 'Ingresar'); ?>
                            Buscar
                        </button>
                    </div>
                    <div class="login-form" style="float:left;">
                        <button style="margin:0 10px 0 0;" type="submit" name="Submit2" onClick="goBack();">
                            <?php echo CHtml::image('../images/back.png', 'Ingresar'); ?>
                            Volver
                        </button>
                    </div>
                </div>
            </div>
            <div id="second-search-right" style="padding-left:10px;">
                <fieldset class="form" style="width:auto">
                    <legend>Resultados</legend>
                    <div id="resultsGeneral">

                    </div>
                </fieldset>
            </div>
        </div>
        <!--			Busqueda general-->

        <!--			Busqueda x rótulos-->
        <div id="second-rotulos-search" style="display:none;">
            <div id="second-search-left">
                <div id="buttons" style="float:left;width: 260px;padding: 30px 0 0 0;">
                    <div class="login-form" style="float:left;">
                        <button style="margin:0 10px 0 0;" type="submit" name="Submit2" onClick="goBack();">
                            <?php echo CHtml::image('../images/back.png', 'Ingresar'); ?>
                            Volver
                        </button>
                    </div>
                    <div class="login-form" style="float:left;">
                        <button style="margin:0 10px 0 0;" type="submit" name="Submit2" onClick="SearchRotulos();">
                            <?php echo CHtml::image('../images/xmag.png', 'Ingresar'); ?>
                            Buscar
                        </button>
                    </div>
                </div>
                <div id="searchBox">
                    <div id="filtersRotulosCarat" style="float:left;">
                        <fieldset class="form" style="width:260px;">
                            <legend>Búsqueda por rótulos</legend>
                            <div id="searchType" style="width: 300px;float:left;padding: 20px 0 10px 0">
                                <div style="float:left;width:100%;padding: 0 0 10px;"><?php echo CHtml::label('Tipo de Busqueda', 'searchType', array('style' => 'text-align: left;')) ?></div>
                                <?php echo CHtml::radioButtonList('searchRotType', '0', array('0' => 'Exacta', '1' => 'Parecida'), array('style' => 'width:50px;float:left;', 'labelOptions' => array('style' => 'width:60px;text-align: left;'), 'separator' => " ")) ?>
                            </div>
                            <div id="MetaRotulosCarats" style="width: 260px;"></div>
                        </fieldset>
                    </div>
                </div>
            </div>
            <div id="second-search-right" style="padding-left:10px;">
                <fieldset class="form" style="width:auto">
                    <legend>Resultados</legend>
                    <div id="resultsRotulos">
                    </div>
                </fieldset>
            </div>
        </div>
        <!--			Busqueda x rótulos-->
        <div id="result"></div>
        <?php
        $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
          'id' => 'showImage',
          // additional javascript options for the dialog plugin
          'options' => array(
            'title' => 'Imagen',
            'modal' => TRUE,
            'resizable' => FALSE,
            'autoOpen' => false,
          ),
        ));
//echo '<div id="image-toolbar" style="float:left;width: 100%;"><div style="float:left">'.CHtml::link(CHtml::image("/images/Arrow-Right.png"),"",array('id'=>'prevImage','style'=>'cursor:pointer;')).'</div>';
//echo '<div style="float:left;padding-left: 50px;">'.CHtml::link(CHtml::image("/images/prev_img.png"),"",array('id'=>'prevSubImage','style'=>'cursor:pointer;display:none')).'</div>';
//echo '<div style="float:right">'.CHtml::link(CHtml::image("/images/Arrow-Left.png"),"",array('id'=>'nextImage','style'=>'cursor:pointer;')).'</div>';
//echo '<div style="float:right;padding-right: 50px;">'.CHtml::link(CHtml::image("/images/next_img.png"),"",array('id'=>'nextSubImage','style'=>'cursor:pointer;display:none')).'</div>';
//echo '</div>';
//echo '<div id="image-cmeta" style="float:left;width: 100%;text-align: center;"></div>';
//echo '<div id="image-meta" style="float:left;width: 100%;text-align: center;"></div>';
//if (Yii::app()->user->isAdmin)
//{
//   echo '<div id="image-path" style="float:left;width: 100%;text-align: center;"></div>';
//}
//else
//{
//    echo '<div id="image-path" style="float:left;display:none;width: 100%;text-align: center;"></div>';
//}
        echo '<div id="image" style="float:left;width:100%"></div>';
//echo '<div id="image-toolbar-footer" style="float:left;width: 100%;"><div style="float:left">'.CHtml::link(CHtml::image("/images/Arrow-Right.png"),"javascript:void(0);",array('id'=>'prevImage-footer','style'=>'cursor:pointer;')).'</div>';
//echo '<div style="float:left;padding-left: 50px;">'.CHtml::link(CHtml::image("/images/prev_img.png"),"javascript:void(0)",array('id'=>'prevSubImage-footer','style'=>'cursor:pointer;display:none')).'</div>';
//echo '<div style="float:right">'.CHtml::link(CHtml::image("/images/Arrow-Left.png"),"",array('id'=>'nextImage-footer','style'=>'cursor:pointer;')).'</div>';
//echo '<div style="float:right;padding-right: 50px;">'.CHtml::link(CHtml::image("/images/next_img.png"),"",array('id'=>'nextSubImage-footer','style'=>'cursor:pointer;display:none')).'</div>';
//echo '</div>';
        $this->endWidget('zii.widgets.jui.CJuiDialog');

        $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
          'id' => 'downloading',
          'htmlOptions' => array('style' => 'background-color:rgb(239, 244, 250)'),
          // additional javascript options for the dialog plugin
          'options' => array(
            'title' => 'Descarga',
            'modal' => TRUE,
            'resizable' => FALSE,
            'autoOpen' => false,
          ),
        ));
        echo '<div id="progress" style="float:left;width: 100%;text-align:center">' . CHtml::image("/images/ajax-loader.gif") . '</div>';
        $this->endWidget('zii.widgets.jui.CJuiDialog');
        ?>
    </div></div>
<div id="meta_form" class="hidden"></div>
<script type="text/javascript">
    var docLevel1Id;
    var docLevel2Id;
    var docLevel3Id;
    var docLevel4Id;
    var searchType;
    var groupType;
    var rotuloId;
    var rotate_angle = 0;
    var ultAnchoImg;

    function toggleGeneralSearch()
    {
        $("#results").empty();
        $("#first-search").slideToggle();
        $("#second-general-search").slideToggle();
        $.ajax({url: "<?php echo Yii::app()->request->hostinfo ?>/site/searchGeneral",
            context: document.body,
            type: "POST",
            dataType: "text",
            success: function(data) {
                $("#filterRestict").empty();
                $("#filterRestict").append(data);
            }
        });
    }



    function SearchDocs(page)
    {
        if (checkGroupType()) {
            if (page == null)
            {
                page = 1;
            }
            if ($("#searchType_0").attr("checked"))
            {
                searchType = "Exacta";
            }
            else
            {
                searchType = "Parecida";
            }
            groupType = ($("#groupType_1").attr("checked")) ? 'image' : 'carat';
            $('html, body').animate({scrollTop: 0}, 'slow');
            docLevel1Id = $("#docLevel1").val();
            docLevel2Id = $("#docLevel2").val();
            docLevel3Id = $("#docLevel3").val();
            docLevel4Id = $("#docLevel4").val();
            $("#results").empty().html('<img src="../images/ajax-loader.gif" />');
            var dataCMeta = $("input[id='CMETA_']").map(function() {
                return $(this).val();
            }).get();
            var CmetaFields = '';
            for (i in dataCMeta)
            {
                CmetaFields = CmetaFields + "&CMETA_[" + i + "]=" + dataCMeta[i];
            }
            var dataOcrMeta = $("input[id='OCR_']").map(function() {
                return $(this).val();
            }).get();
            var OcrFields = '';
            for (i in dataOcrMeta)
            {
                OcrFields = OcrFields + "&OCR_[" + i + "]=" + dataOcrMeta[i];
            }
            $.ajax({url: "<?php echo Yii::app()->request->hostinfo ?>/searchByDocType/searchByDocType",
                context: document.body,
                type: "POST",
                data: "page=" + page + "&docLevel1=" + docLevel1Id + "&docLevel2=" + docLevel2Id + "&docLevel3=" + docLevel3Id + "&docLevel4=" + docLevel4Id + CmetaFields + OcrFields + "&searchType=" + searchType + "&groupType=" + groupType,
                dataType: "text",
                success: function(data) {
                    $("#results").empty();
                    $("#results").append(data);
                }
            });
        }
        else {
            alert('Si va a agrupar por imagen, debe filtrar su búsqueda con al menos 1 metadato de imagen.');
            $("#groupType_0").attr("checked", "checked");
        }
    }

    function SearchGralDocs(page, docType)
    {
        var Docs = [];
        var doc = $("#searchField").val();
        if (page == null)
        {
            page = 1;
        }
        if ($("#searchGeneralType_0").attr("checked"))
        {
            searchType = "Exacta";
        }
        else
        {
            searchType = "Parecida";
        }
        $("#filterRestict :checked").each(function() {
            Docs.push($(this).val());
        });
        if (Docs == "") {
            alert("Debe seleccionar al menos un tipo documento.");
        }
        else if (doc == "") {
            alert("La búsqueda no se puede realizar, si el campo 'Buscar', está vacío.");
        }
        else {
            $("#resultsGeneral").empty().html('<img src="../images/ajax-loader.gif" />');
            $.ajax({url: "<?php echo Yii::app()->request->hostinfo ?>/site/searchGeneralDoc",
                context: document.body,
                type: "POST",
                data: "page=" + page + "&docs=" + Docs + "&field=" + doc + "&searchType=" + searchType + "&docType=" + docType,
                dataType: "text",
                success: function(data) {
                    $("#resultsGeneral").empty();
                    $("#resultsGeneral").append(data);
                }
            });
        }
    }

    function SearchRotulos(page)
    {
        if (page == null)
        {
            page = 1;
        }
        if ($("#searchRotType_0").attr("checked"))
        {
            searchType = "Exacta";
        }
        else
        {
            searchType = "Parecida";
        }
        $("#resultsRotulos").empty().html('<img src="../images/ajax-loader.gif" />');
        var dataCMeta = $("input[id='CMETA_']").map(function() {
            return $(this).val();
        }).get();
        var CmetaFields = '';
        for (i in dataCMeta)
        {
            CmetaFields = CmetaFields + "&CMETA_[" + i + "]=" + dataCMeta[i];
        }
        $.ajax({url: "<?php echo Yii::app()->request->hostinfo ?>/site/searchByRotulo",
            context: document.body,
            type: "POST",
            data: "page=" + page + "&rotulo=" + rotuloId + CmetaFields + "&searchType=" + searchType,
            dataType: "text",
            success: function(data) {
                $("#resultsRotulos").empty();
                $("#resultsRotulos").append(data);
            }
        });
    }

    function showImage(id, currIndex, currSubIndex)
    {
        $("#image").html('<img src="../images/ajax-loader.gif" />');
        var imageList = $("#imageList" + id).html();
        var imageList2 = $("#imageList2" + id).html();
        rotate_angle = 0;
        $("#image").empty();
        var widthSize = ($(window).width() - 200);
        ultAnchoImg = widthSize;
        $.ajax({url: "<?php echo Yii::app()->request->hostinfo ?>/site/viewImage",
            context: document.body,
            type: "POST",
            data: "imageList=" + imageList + "&imageList2=" + imageList2 + "&widthsize=" + widthSize + "&currIndex=" + currIndex + "&currSubIndex=" + currSubIndex + "&rotar=" + rotate_angle,
            dataType: "text",
            success: function(data) {
                $("#image").css("text-align", "center");
                $("#image").empty();
                $("#image-meta").empty();
                $("#image").children().remove();
                $("#image").append(data);
            }});
        $("#showImage").dialog({width: widthSize});
        $("#showImage").dialog({height: $(window).height() - 50});
        $("#showImage").dialog("open");
    }
    function showImageSmall(id, currIndex, currSubIndex)
    {
        $("#image").html('<img src="../images/ajax-loader.gif" />');
        var imageList = $("#imageList" + id).html();
        var imageList2 = $("#imageList2" + id).html();
        rotate_angle = 0;
        $("#image").empty();
        var widthSize = ($(window).width() - 200);
        ultAnchoImg = 500;
        $.ajax({url: "<?php echo Yii::app()->request->hostinfo ?>/site/viewImage",
            context: document.body,
            type: "POST",
            data: "imageList=" + imageList + "&imageList2=" + imageList2 + "&widthsize=500&currIndex=" + currIndex + "&currSubIndex=" + currSubIndex + "&rotar=" + rotate_angle,
            dataType: "text",
            success: function(data) {
                $("#image").css("text-align", "center");
                $("#image").empty();
                $("#image-meta").empty();
                $("#image").children().remove();
                $("#image").append(data);
            }});
        $("#showImage").dialog({width: widthSize});
        $("#showImage").dialog({height: $(window).height() - 50});
        $("#showImage").dialog("open");
    }

    function rotarImagen(id, currIndex, currSubIndex)
    {
        $("#image").html('<img src="../images/ajax-loader.gif" />');
        var imageList = $("#imageList" + id).html();
        var imageList2 = $("#imageList2" + id).html();
        $("#image").empty();
        var widthSize = ($(window).width() - 200);
        rotate_angle = (rotate_angle >= 270) ? 0 : rotate_angle + 90;
        //$('#imgprincipal').rotate({ angle : rotate_angle });
        $.ajax({url: "<?php echo Yii::app()->request->hostinfo ?>/site/viewImage",
            context: document.body,
            type: "POST",
            data: "imageList=" + imageList + "&imageList2=" + imageList2 + "&widthsize=" + ultAnchoImg + "&currIndex=" + currIndex + "&currSubIndex=" + currSubIndex + "&rotar=" + rotate_angle,
            dataType: "text",
            success: function(data) {
                $("#image").css("text-align", "center");
                $("#image").empty();
                $("#image-meta").empty();
                $("#image").children().remove();
                $("#image").append(data);

            }});
        $("#showImage").dialog({width: widthSize});
        $("#showImage").dialog({height: $(window).height() - 50});
        $("#showImage").dialog("open");

    }

    function getImage(path, widthSize, doctype, docSubtype, meta, cmeta)
    {
        $("#image").empty();
        $("#image").css("text-align", "center");
        $("#image").html('<img src="../images/ajax-loader.gif" />');
        $.ajax({url: "<?php echo Yii::app()->request->hostinfo ?>/site/view",
            context: document.body,
            type: "POST",
            data: "path=" + path + "&widthsize=" + widthSize + "&doctype=" + doctype + "&docSubtype=" + docSubtype,
            dataType: "text",
            success: function(data) {
                $("#image").empty();
                getSubImages(path, widthSize, doctype, docSubtype, meta, cmeta);
                getPrevImage(path, widthSize, doctype, docSubtype, meta, cmeta);
                getNextImage(path, widthSize, doctype, docSubtype, meta, cmeta);
                $("#image-meta").empty();
                if (meta != "false")
                    $("#image-meta").append(meta);
                $("#image-path").empty();
                $("#image-path").append("Ruta: " + path);
                $("#image-cmeta").empty();
                $("#image-cmeta").append(cmeta);
                if (docSubtype == "OZ") {
                    $("#image").css("margin-left", "0 50px 0 50px");
                }
                else {
                    $("#image").css("margin", "0 50px 0 50px");
                }
                $("#image").append(data);
            }});
    }

    function setOrder(id, order, c1, c2, c3, c4) {
        oldPos = order;
        newPos = $("#orden_" + id).val();
        if (isNaN(newPos)) {
            alert("Escriba solo numeros");
            $("#orden_" + id).val(oldPos);
        }
        else {
            if (newPos <= 0) {
                alert("La posición debe ser mayor a cero.");
                $("#orden_" + id).val(oldPos);
            }
            else {
                $.ajax({url: "<?php echo Yii::app()->request->hostinfo ?>/site/setOrder",
                    context: document.body,
                    type: "POST",
                    data: "id=" + id + "&oldPos=" + oldPos + "&newPos=" + newPos + "&c1=" + c1 + "&c2=" + c2 + "&c3=" + c3 + "&c4=" + c4,
                    dataType: "text",
                    success: function(data) {
                        if (data != '')
                        {
                            alert(data);
                        }
                        else
                        {
                            alert("Refresque la búsqueda, para ver reflejados los cambios.");
                        }
                    }});
            }
        }
    }

    function exportZIP(id)
    {
        $("#downloading").dialog({title: "Exportando a ZIP"})
        $("#downloading").dialog("open");
        var query = $("#query_" + id).html();
//                window.open("<?php echo Yii::app()->request->hostinfo ?>/site/exportZIP/?conditions="+query);
        $.ajax({url: "<?php echo Yii::app()->request->hostinfo ?>/site/exportZIP",
            context: document.body,
            type: "POST",
            data: "conditions=" + query,
            dataType: "text",
            success: function(data) {
                $("#downloading").dialog("close");
                window.open("<?php echo Yii::app()->request->hostinfo ?>/site/GetZip/?fileName=" + data);
            }
        });
    }

    function exportPDF(id)
    {
        $("#downloading").dialog({title: "Exportando a PDF"})
        $("#downloading").dialog("open");
        var query = $("#query_" + id).html();
        $.ajax({url: "<?php echo Yii::app()->request->hostinfo ?>/site/exportPDF",
            context: document.body,
            type: "POST",
            data: "conditions=" + query,
            dataType: "text",
            success: function(data) {
                $("#downloading").dialog("close");
                window.open("<?php echo Yii::app()->request->hostinfo ?>/site/GetPdf/?fileName=" + data);
            }
        });

//                var query =$("#query_"+id).html();
//		window.open("<?php // echo Yii::app()->request->hostinfo   ?>/site/exportPDF/?conditions="+query);
    }


    /**
     * Comprueba que al menos exista un filtro de metadato con valor.
     */
    function checkGroupType() {
        if ($("#groupType_1").attr("checked")) {
            var $inputs = $(":input.metadata");
            var values = new Array();
            var x = 0;
            $inputs.each(function() {
                values[x] = $(this).val();
                x++;
            });
            for (i in values) {
                if (values[i] != '')
                    return true;
            }
            return false;
        }
        return true;
    }

    function getImageInfo(items, id)
    {
        var infoId = items;
        id = infoId;
        var query = $("#query_" + id).html();

        query = encodeURIComponent(query);

        items = $("#imageData" + items).html();
        if (!$("#row" + id).hasClass('fetched'))
        {
            $("#downloading").dialog({title: "Cargando Datos"})
            $("#downloading").dialog("open");
            $("#row" + id).addClass('fetched');
            $.ajax({url: "<?php echo Yii::app()->request->hostinfo ?>/site/getImagesById",
                context: document.body,
                type: "POST",
                data: "items=" + items + "&infoId=" + infoId + "&query=" + query,
                dataType: "json",
                success: function(data) {
                    $("#row" + id).prepend(data.html);
                    $("#imageData" + id).empty();
                    $("#imageData" + id).html(data.imageData);
                    $("#downloading").dialog("close");
                }
            });
        }
        $("#row" + id).toggle();
    }
    function seeMore(id)
    {
        $("#downloading").dialog({title: "Cargando Datos"})
        $("#downloading").dialog("open");
        var query = $("#query_" + id).html();
        var imageList = $("#imageList" + id).html();
        $.ajax({url: "<?php echo Yii::app()->request->hostinfo ?>/site/seeMore",
            context: document.body,
            type: "POST",
            data: "query=" + query + "&imageList=" + imageList,
            dataType: "json",
            success: function(data) {
                $("#" + data.id).append(data.table);
                $("#imageList" + id).empty();
                $("#imageList" + id).html(data.imageList);
                if (!data.hasMore) {
                    $("#seeMore" + data.id).remove();
                }
                $("#downloading").dialog("close");
            }
        });
    }

    function goBack() {
        window.location.replace("<?php echo Yii::app()->request->hostinfo ?>");
    }

    function toogleCaratVisibility(id)
    {
        if ($("#check_set_" + id).is(':checked'))
        {
            action = "show";
        }
        else
        {
            action = "hide";
        }
        var query = $("#query_" + id).html();
        var fields = $("#fields_" + id).html();
        $.ajax({url: "<?php echo Yii::app()->request->hostinfo ?>/site/toogleCaratVisibility",
            context: document.body,
            type: "POST",
            data: "id=" + id + "&query=" + query + "&fields=" + fields + "&action=" + action,
            dataType: "json",
            success: function(data) {
                if (action == 'show') {
                    $(".check_" + id).attr('checked', true);
                }
                else {
                    $(".check_" + id).attr('checked', false);
                }
                $("#imageData" + id).html(data.image);
                alert(data.message);
            }
        });
    }

    function toogleImageVisibility(id, currIndex)
    {
        if ($("#check_" + id + "_" + currIndex).is(':checked'))
        {
            action = "show";
        }
        else
        {
            action = "hide";
        }
        var imageList = $("#imageList" + id).html();
        $.ajax({url: "<?php echo Yii::app()->request->hostinfo ?>/site/toogleImageVisibility",
            context: document.body,
            type: "POST",
            data: "imageList=" + imageList + "&currIndex=" + currIndex + "&action=" + action,
            dataType: "text",
            success: function(data) {
                alert(data);
            }
        });
    }

    function checkImageSelection() {
        if (checkGroupType()) {
            SearchDocs();
        }
        else {
            alert('Si va a agrupar por imagen, debe filtrar su búsqueda con al menos 1 metadato de imagen.');
            $("#groupType_0").attr("checked", "checked");
        }
    }
    $(document).ready(function() {
        $("#meta_form").dialog({
            autoOpen: false,
            modal: true,
            resizable: true,
            title: "Modificar Metadatos",
            width: 550
        });
    });

    function openMetaForm(id) {
        $("#meta_form").dialog("option", "buttons", [
            {text: "Actualizar", click: function() {
                    editImage(id);
                }},
            {text: "Cancelar", click: function() {
                    $(this).dialog("close");
                }}
        ]);
        getMetaData(id);
    }
    function getMetaData(id) {

        $.ajax({
            url: "<?php echo Yii::app()->request->hostinfo ?>/ocrmeta/GetImageMeta",
            type: "POST",
            data: {mongo_id: id},
            dataType: "json",
            success: function(data) {
                $("#meta_form").html(data.html);
                $("#meta_form").dialog("open");
            }
        });
    }
    function editImage(id) {
        var inputs = $('#new_meta_form :input');
        var newData = {};
        $.each(inputs, function( ) {
            $(newData).attr(this.id, $(this).val());

        });

        $.ajax({
            url: "<?php echo Yii::app()->request->hostinfo ?>/ocrmeta/editImageMeta",
            context: document.body,
            type: "POST",
            data: {mongo_id: id, new_data: newData},
            dataType: "json",
            success: function(data) {
                if (data.success) {
                    updateGridValues(id, newData, "meta_form");
                }
                else
                {
                    alert(data.message);
                    $("#meta_form").dialog("close");
                }
            }
        });
    }
</script>