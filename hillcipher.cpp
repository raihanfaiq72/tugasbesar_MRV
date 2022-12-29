#include<iostream>
#include<iomanip>
#include<math.h>

using namespace std;

float encrypt[3][3], decrypt[3][3], a[3][3], b[3][3], mes[3][3], c[3][3];
char msg[9];
char pesan[3][3];
void enkripsi();
void dekripsi();
void getMatriks();
void inverse();

int main() {
  getMatriks();
  cout << "========================Enkripsi========================" << endl;
  enkripsi();
  cout << "\n========================Dekripsi========================" << endl;
  dekripsi();
}

void getMatriks() {
    int i, j;
    int k=0;
    int l=0;
    int m=0;
    string key = "udinusmrv";

    cout << "Matriks kunci = " << key;
    cout << "\nMatriks dari [" << key << "] adalah = " << endl;
    for (i = 0; i < 3; i++){
        for (j = 0; j < 3; j++) {
            c[i][j] = key[k] - 97;
            a[i][j] = c[i][j];
            cout << a[i][j] << " ";
            k++;
        }
        cout << endl;
    }

    cout << "\nMasukan 3 huruf: ";
    cin >> msg;

    cout << "\nMatriks dari [" << msg << "] adalah = " << endl;
//    for (i = 0; i < 3; i++){
//        mes[i][0] = msg[i] - 97;
//        cout << mes[i][0] << endl;
//    }
    for (i = 0; i < 3; i++){
        for (j = 0; j < 3; j++) {
            pesan[i][j] = msg[m];
            cout << pesan[i][j] << " ";
            m++;
        }
        cout << endl;
    }
    cout << endl;
    for (i = 0; i < 3; i++){
        for (j = 0; j < 3; j++) {
            mes[i][j] = msg[l] - 97;
            cout << mes[i][j] << " ";
            l++;
        }
        cout << endl;
    }
    cout << endl;
}

void enkripsi() {
    int i, j, k;

    cout << "Matrik kunci dikali dengan matriks [" << msg << "]"<< endl;

    for (i = 0; i < 3; i++){
        for (j = 0; j < 3; j++){
            for (k = 0; k < 3; k++){
                encrypt[i][j] += a[i][k] * mes[k][j];
            }
            cout << a[i][j] << " ";
        }
        cout << endl;
    }

    cout << "*" << endl;

//    for (i = 0; i < 3; i++){
//        cout << mes[i][0] << endl;
//    }
    for (i = 0; i < 3; i++){
        for (j = 0; j < 1; j++){
            for (k = 0; k < 3; k++){
                cout << mes[i][k] << " ";
            }
            cout << endl;
        }
    }
    cout << "\nHasil perkalian matriks [" << msg << "] dengan matriks kunci = " << endl;
//    for (i = 0; i < 3; i++){
//        cout << encrypt[i][0] << endl;
//    }
    for (i = 0; i < 3; i++){
        for (j = 0; j < 3; j++){
            cout << encrypt[i][j] << " ";
        }
        cout << endl;
    }

    cout << "\nHasil perkalian matriks [" << msg << "] dengan matriks kunci dimodulo 26 = " << endl;
//    for (i = 0; i < 3; i++){
//        cout << fmod(encrypt[i][0], 26) << endl;
//    }
    for (i = 0; i < 3; i++){
        for (j = 0; j < 3; j++){
            cout << fmod(encrypt[i][j], 26) << " ";
        }
        cout << endl;
    }

    cout << "\nHasil enkripsi: ";
//    for (i = 0; i < 3; i++){
//        cout << (char)(fmod(encrypt[i][0], 26) + 97);
//    }
    for (i = 0; i < 3; i++){
        for (j = 0; j < 3; j++){
            cout << (char)(fmod(encrypt[i][j], 26) + 97);
        }
    }
}

void dekripsi() {
    int i, j, k;

    inverse();

    cout << "\nInverse matriks kunci dikali dengan matriks enkripsi [" << msg << "] = " << endl;
    for (i = 0; i < 3; i++){
        for (j = 0; j < 1; j++){
            for (k = 0; k < 3; k++){
                cout << b[i][k] << " ";
            }
        }
        cout << endl;
    }

    cout << "*" << endl;

    for (i = 0; i < 3; i++){
        for (j = 0; j < 3; j++){
            cout << encrypt[i][j] << " ";
        }
        cout << endl;
    }

    for (i = 0; i < 3; i++){
        for (j = 0; j < 3; j++){
            for (k = 0; k < 3; k++){
                decrypt[i][j] += b[i][k] * encrypt[k][j];
            }
        }
    }

    cout << "\nHasil perkalian matriks inverse dengan matriks enkripsi [" << msg << "] = " << endl;
//    for (i = 0; i < 3; i++){
//        cout << decrypt[i][0] << endl;
//    }
    for (i = 0; i < 3; i++){
        for (j = 0; j < 3; j++){
            cout << decrypt[i][j] << " ";
        }
        cout << endl;
    }

    cout << "\nHasil perkalian matriks inverse dengan matriks enkripsi [" << msg << "] dimodulo 26 = " << endl;
//    for (i = 0; i < 3; i++){
//        cout << fmod(decrypt[i][0], 26) << endl;
//    }
    for (i = 0; i < 3; i++){
        for (j = 0; j < 3; j++){
            cout << fmod(decrypt[i][j], 26) << " ";
        }
        cout << endl;
    }

    cout << "\nHasil dekripsi: ";
//    for (i = 0; i < 3; i++){
//        cout << (char)(fmod(decrypt[i][0], 26) + 97);
//    }
    for (i = 0; i < 3; i++){
        for (j = 0; j < 3; j++){
            cout << (char) (fmod(decrypt[i][j], 26)+ 97);
        }
    }
    cout << "\n";
}

void inverse() {
    int i, j, k;
    float p, q;
    for (i = 0; i < 3; i++){
        for (j = 0; j < 3; j++) {
            if (i == j)
                b[i][j] = 1;
            else
                b[i][j] = 0;
        }
    }
    for (k = 0; k < 3; k++) {
        for (i = 0; i < 3; i++) {
            p = c[i][k];
            q = c[k][k];
        for (j = 0; j < 3; j++) {
                if (i != k) {
                    c[i][j] = c[i][j] * q - p * c[k][j];
                    b[i][j] = b[i][j] * q - p * b[k][j];
                }
            }
        }
    }
    for (i = 0; i < 3; i++){
        for (j = 0; j < 3; j++){
            b[i][j] = b[i][j] / c[i][i];
        }
    }


    cout << "\nInverse matriks kunci:\n";
    for (i = 0; i < 3; i++) {
        for (j = 0; j < 3; j++){
            cout << b[i][j] << " ";
        }
    cout << endl;
    }
}
