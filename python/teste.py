import chainer
from chainercv import utils
from chainercv.datasets import voc_bbox_label_names
from chainercv.links import SSD300
from chainercv.visualizations import vis_bbox
chainer.config.train = False
model = SSD300(
    n_fg_class=len(voc_bbox_label_names), pretrained_model='voc0712')
model.score_thresh = 0.30
chainer.serializers.load_npz('./model', model)

img_path = utils.cached_download('https://image.freepik.com/free-vector/potted-plant-doodle-cartoon-drawing-set-collection_58032-553.jpg')
img = utils.read_image(img_path, color=True)
bboxes, labels, scores = model.predict([img])

mapped_labels = list(map( lambda x: voc_bbox_label_names[x], labels[0]))
print(mapped_labels)