import cv2
import numpy as np
import matplotlib.pyplot as plt

# Load gambar
img = cv2.imread('percobaan.jpg')
gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)

# 1. Otsu Thresholding
_, otsu = cv2.threshold(gray, 0, 255, cv2.THRESH_BINARY + cv2.THRESH_OTSU)

# 2. Canny Edge Detection
canny = cv2.Canny(gray, 100, 200)

# 3. Edge Detection (Sobel)
sobelx = cv2.Sobel(gray, cv2.CV_64F, 1, 0, ksize=3)
sobely = cv2.Sobel(gray, cv2.CV_64F, 0, 1, ksize=3)
edge = cv2.magnitude(sobelx, sobely)
edge = cv2.convertScaleAbs(edge)

# 4. Template Matching
template = gray[50:150, 50:150]  # potongan template buatan
w, h = template.shape[::-1]
res = cv2.matchTemplate(gray, template, cv2.TM_CCOEFF_NORMED)
min_val, max_val, min_loc, max_loc = cv2.minMaxLoc(res)
matching = img.copy()
cv2.rectangle(matching, max_loc, (max_loc[0] + w, max_loc[1] + h), (0, 255, 0), 2)

# 5. Watershed
ret, thresh = cv2.threshold(gray, 0, 255, cv2.THRESH_BINARY_INV + cv2.THRESH_OTSU)
# Noise removal
kernel = np.ones((3,3),np.uint8)
opening = cv2.morphologyEx(thresh, cv2.MORPH_OPEN, kernel, iterations=2)
# Sure background
sure_bg = cv2.dilate(opening, kernel, iterations=3)
# Finding sure foreground area
dist_transform = cv2.distanceTransform(opening, cv2.DIST_L2, 5)
ret, sure_fg = cv2.threshold(dist_transform, 0.7*dist_transform.max(), 255, 0)
# Finding unknown region
sure_fg = np.uint8(sure_fg)
unknown = cv2.subtract(sure_bg, sure_fg)
# Marker labeling
ret, markers = cv2.connectedComponents(sure_fg)
markers = markers + 1
markers[unknown == 255] = 0
watershed_img = img.copy()
markers = cv2.watershed(watershed_img, markers)
watershed_img[markers == -1] = [255, 0, 0]  # boundary = red

# 6. Hough Transform (deteksi garis)
edges_hough = cv2.Canny(gray, 50, 150, apertureSize=3)
lines = cv2.HoughLines(edges_hough, 1, np.pi/180, 150)
hough = img.copy()
if lines is not None:
    for rho, theta in lines[:, 0]:
        a = np.cos(theta)
        b = np.sin(theta)
        x0 = a*rho
        y0 = b*rho
        x1 = int(x0 + 1000*(-b))
        y1 = int(y0 + 1000*(a))
        x2 = int(x0 - 1000*(-b))
        y2 = int(y0 - 1000*(a))
        cv2.line(hough, (x1, y1), (x2, y2), (0, 0, 255), 2)

# Tampilkan hasil
titles = ['Original', 'Otsu', 'Canny', 'Sobel Edge', 'Template Matching', 'Watershed', 'Hough']
images = [img, otsu, canny, edge, matching, watershed_img, hough]

plt.figure(figsize=(14, 10))
for i in range(7):
    plt.subplot(3, 3, i+1)
    if i == 0 or i == 4 or i == 5 or i == 6:
        plt.imshow(cv2.cvtColor(images[i], cv2.COLOR_BGR2RGB))
    else:
        plt.imshow(images[i], cmap='gray')
    plt.title(titles[i])
    plt.axis('off')

plt.tight_layout()
plt.show()
